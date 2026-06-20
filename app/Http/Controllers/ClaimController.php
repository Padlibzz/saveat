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

        return response()->json(['status' => 'success', 'data' => $claims], 200);
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

        return DB::transaction(function () use ($request) {
            $listing = Listing::lockForUpdate()->with('merchant')->findOrFail($request->listing_id);

            if (now()->greaterThan($listing->batas_waktu)) {
                return response()->json(['status' => 'error', 'message' => 'Makanan sudah melewati batas waktu.'], 400);
            }
            if (! in_array($listing->status, [ListingStatus::AKTIF->value, ListingStatus::HAMPIR_HABIS->value])) {
                return response()->json(['status' => 'error', 'message' => 'Listing ini sudah tidak tersedia.'], 400);
            }
            if ($listing->stok_sisa < $request->jumlah) {
                return response()->json(['status' => 'error', 'message' => 'Stok sisa tidak mencukupi.'], 400);
            }

            $hargaSatuan = $listing->harga_diskon ?? 0;
            $subtotal = $hargaSatuan * $request->jumlah;
            $pajak = $subtotal * 0.11; // Contoh PPN 11%
            $totalHarga = $subtotal + $pajak;

            $kodeKlaim = 'CLM-'.strtoupper(Str::random(8));

            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'listing_id' => $request->listing_id,
                'jumlah' => $request->jumlah,
                'total_harga' => $totalHarga,
                'kode_klaim' => $kodeKlaim,
                'status_pembayaran' => PaymentStatus::BELUM_DIBAYAR->value,
                'status' => ClaimStatus::PENDING->value,
            ]);

            $listing->stok_sisa -= $request->jumlah;
            $listing->save();

            $persenSisa = $listing->stok_sisa / max($listing->stok_total, 1);
            if ($listing->stok_sisa <= 0) {
                $listing->update(['status' => ListingStatus::TUTUP->value]);
            } elseif ($listing->status === ListingStatus::AKTIF->value && $persenSisa < 0.2) {
                $listing->update(['status' => ListingStatus::HAMPIR_HABIS->value]);
            }

            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            $params = [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $kodeKlaim,
                    'gross_amount' => (int) $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'phone' => $request->user()->no_telphone,
                ],
                'item_details' => [
                    [
                        'id' => $listing->id,
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

            try {
                $midtransResponse = CoreApi::charge($params);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal terhubung ke gerbang pembayaran: '.$e->getMessage(),
                ], 500);
            }

            NotificationService::menungguPembayaran($request->user()->id, $claim->id, $listing->nama);

            $qrPembayaranUrl = null;
            if (isset($midtransResponse->actions)) {
                foreach ($midtransResponse->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $qrPembayaranUrl = $action->url;
                        break;
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan dibuat. Silakan scan QR untuk membayar.',
                'data' => [
                    'claim' => $claim,
                    'rincian' => [
                        'subtotal' => $subtotal,
                        'pajak' => $pajak,
                        'total' => $totalHarga,
                    ],
                    'qr_pembayaran_url' => $qrPembayaranUrl,
                    'kode_klaim_untuk_merchant' => $kodeKlaim,
                ],
            ], 201);
        });
    }

    public function paymentCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $claim = Claim::where('kode_klaim', $request->order_id)->first();
        if (! $claim) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {

            $claim->update(['status_pembayaran' => PaymentStatus::SUDAH_DIBAYAR->value]);

        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {

            $claim->update([
                'status_pembayaran' => PaymentStatus::GAGAL->value,
                'status' => ClaimStatus::BATAL->value,
            ]);

            $listing = Listing::find($claim->listing_id);
            if ($listing) {
                $listing->stok_sisa += $claim->jumlah;
                $listing->save();
            }
        }

        return response()->json(['message' => 'Callback processed']);
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
