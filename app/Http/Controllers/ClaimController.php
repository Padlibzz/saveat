<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Enums\ListingStatus;
use App\Enums\PaymentStatus;
use App\Models\Claim;
use App\Models\Listing;
use App\Services\NotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $claims = Claim::with([
            'listing:id,nama,foto,status,batas_waktu,merchant_id',
            'listing.merchant:id,nama_usaha',
            'listing.kategori:id,nama',
        ])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($klaim) {
                $klaim->status_riwayat = $this->resolveStatusRiwayat($klaim);

                return $klaim;
            });

        // Ringkasan untuk dashboard konsumen
        $klaimValid = $claims->where('status', '!=', 'batal');

        $totalHemat = $klaimValid->sum(function ($klaim) {
            $hargaNormal = $klaim->listing->harga_normal ?? 0;

            return ($hargaNormal - ($klaim->total_harga / max($klaim->jumlah, 1))) * $klaim->jumlah;
        });

        return response()->json([
            'status' => 'success',
            'data' => $claims,
            'summary' => [
                'total_klaim' => $klaimValid->count(),
                'makanan_terselamatkan' => (int) $klaimValid->sum('jumlah'),
                'total_hemat' => (float) max($totalHemat, 0),
            ],
        ], 200);
    }

    private function resolveStatusRiwayat(Claim $klaim): string
    {
        if ($klaim->status === ClaimStatus::DIAMBIL->value) {
            return 'sudah_diambil';
        }
        if ($klaim->status === ClaimStatus::BATAL->value) {
            return 'kadaluarsa';
        }
        if ($klaim->listing && $klaim->listing->status === ListingStatus::TUTUP->value) {
            return 'kadaluarsa';
        }

        return 'aktif';
    }

    public function paymentMethods()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                ['id' => 'qris', 'nama' => 'QRIS', 'kategori' => 'qr_code'],
                ['id' => 'dana', 'nama' => 'DANA', 'kategori' => 'e_wallet'],
            ],
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'jumlah' => 'required|integer|min:1',
            'payment_type' => 'required|in:qris,dana',
        ]);

        // Mulai transaksi DB secara manual agar bisa di-rollback jika Midtrans gagal
        DB::beginTransaction();

        try {
            $listing = Listing::lockForUpdate()->with('merchant')->findOrFail($request->listing_id);

            if (now()->greaterThan($listing->batas_waktu)) {
                throw new Exception('Makanan sudah melewati batas waktu.');
            }
            if (! in_array($listing->status, [ListingStatus::AKTIF->value, ListingStatus::HAMPIR_HABIS->value])) {
                throw new Exception('Listing ini sudah tidak tersedia.');
            }
            if ($listing->stok_sisa < $request->jumlah) {
                throw new Exception('Stok sisa tidak mencukupi.');
            }

            // Kalkulasi
            $hargaSatuan = $listing->harga_diskon ?? 0;
            $subtotal = $hargaSatuan * $request->jumlah;
            $pajak = $subtotal * 0.11; // PPN 11%
            $totalHarga = $subtotal + $pajak;

            $kodeKlaim = 'CLM-'.strtoupper(Str::random(8));

            // Buat Pesanan
            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'listing_id' => $request->listing_id,
                'jumlah' => $request->jumlah,
                'total_harga' => $totalHarga,
                'kode_klaim' => $kodeKlaim,
                'metode_pembayaran' => $request->payment_type, // Simpan metode
                'status_pembayaran' => PaymentStatus::BELUM_DIBAYAR->value,
                'status' => ClaimStatus::PENDING->value,
            ]);

            // Potong Stok
            $listing->stok_sisa -= $request->jumlah;
            $listing->save();

            // Update Status Listing
            $persenSisa = $listing->stok_sisa / max($listing->stok_total, 1);
            if ($listing->stok_sisa <= 0) {
                $listing->update(['status' => ListingStatus::TUTUP->value]);
            } elseif ($listing->status === ListingStatus::AKTIF->value && $persenSisa < 0.2) {
                $listing->update(['status' => ListingStatus::HAMPIR_HABIS->value]);
            }

            // --- INTEGRASI MIDTRANS CORE API ---
            Config::$serverKey = config('midtrans.server_key'); // Gunakan dari config
            Config::$isProduction = config('midtrans.is_production');

            $params = [
                'payment_type' => $request->payment_type,
                'transaction_details' => [
                    'order_id' => $kodeKlaim, // Order ID di Midtrans adalah Kode Klaim
                    'gross_amount' => (int) $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'phone' => $request->user()->no_telphone ?? '',
                ],
                'item_details' => [
                    [
                        'id' => (string) $listing->id,
                        'price' => $hargaSatuan,
                        'quantity' => $request->jumlah,
                        'name' => substr($listing->nama, 0, 50),
                    ],
                    [
                        'id' => 'TAX',
                        'price' => (int) $pajak,
                        'quantity' => 1,
                        'name' => 'Pajak (11%)',
                    ],
                ],
            ];

            // Tembak ke Midtrans
            $midtransResponse = CoreApi::charge($params);

            $paymentUrl = null;

            // Ekstraksi URL Pembayaran (Beda untuk QRIS dan DANA)
            if ($request->payment_type === 'qris' && isset($midtransResponse->actions)) {
                foreach ($midtransResponse->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $paymentUrl = $action->url; // URL Gambar QR Code
                        break;
                    }
                }
            } elseif ($request->payment_type === 'dana' && isset($midtransResponse->actions)) {
                foreach ($midtransResponse->actions as $action) {
                    // DANA biasanya mengembalikan deeplink/web checkout
                    if (in_array($action->name, ['generate-qr-code', 'generate-checkout-url', 'generate-deep-link'])) {
                        $paymentUrl = $action->url;
                        break;
                    }
                }
            }

            NotificationService::menungguPembayaran($request->user()->id, $claim->id, $listing->nama);

            // Jika semua sukses, simpan transaksi database
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan dibuat. Silakan selesaikan pembayaran.',
                'data' => [
                    'claim_id' => $claim->id,
                    'kode_klaim' => $kodeKlaim,
                    'rincian' => [
                        'jumlah' => $request->jumlah,
                        'subtotal' => $subtotal,
                        'pajak' => $pajak,
                        'total' => $totalHarga,
                    ],
                    'metode_pembayaran' => $request->payment_type,
                    'payment_url' => $paymentUrl, // Tampilkan ini di UI (berupa QR img / tombol link DANA)
                ],
            ], 201);

        } catch (Exception $e) {
            // BATALKAN SEMUA PERUBAHAN DATABASE (stok tidak jadi berkurang)
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses pesanan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function scanQr(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant || $merchant->status_verifikasi !== 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Aksi ditolak. Akun Merchant tidak valid.',
            ], 403);
        }

        $request->validate(['kode_klaim' => 'required|string']);

        $claim = Claim::with('listing')->where('kode_klaim', $request->kode_klaim)->first();

        if (! $claim) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid atau pesanan tidak ditemukan.',
            ], 404);
        }

        if ($claim->listing->merchant_id !== $merchant->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ini bukan pesanan untuk toko Anda.',
            ], 403);
        }

        if ($claim->status_pembayaran !== PaymentStatus::SUDAH_DIBAYAR->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Konsumen belum menyelesaikan pembayaran.',
            ], 400);
        }

        if ($claim->status === ClaimStatus::DIAMBIL->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code ini sudah pernah digunakan.',
            ], 400);
        }

        $claim->update(['status' => ClaimStatus::DIAMBIL->value]);

        NotificationService::pesananSelesai($claim->user_id, $claim->id, $claim->listing->nama);

        return response()->json([
            'status' => 'success',
            'message' => 'Scan berhasil! Makanan bisa diserahkan ke konsumen.',
            'data' => $claim,
        ], 200);
    }

    public function selesai(Request $request, $id)
    {
        $claim = Claim::with('listing')->findOrFail($id);

        if ($claim->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak valid.',
            ], 403);
        }

        if ($claim->status === ClaimStatus::BATAL->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan sudah dibatalkan.',
            ], 400);
        }

        if ($claim->status === ClaimStatus::DIAMBIL->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan sudah diselesaikan sebelumnya.',
            ], 400);
        }

        if ($claim->status_pembayaran !== PaymentStatus::SUDAH_DIBAYAR->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan belum dibayar.',
            ], 400);
        }

        $claim->update(['status' => ClaimStatus::DIAMBIL->value]);

        NotificationService::pesananSelesai($claim->user_id, $claim->id, $claim->listing->nama);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil diselesaikan.',
            'data' => $claim,
        ], 200);
    }
}
