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
    public function pesananAktif(Request $request)
    {
        $claims = Claim::with(['listing.merchant'])
            ->where('user_id', $request->user()->id)
            ->where('status', '!=', ClaimStatus::DIAMBIL->value) // Filter bukan yang sudah selesai
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pesanan', compact('claims'));
    }

    public function riwayat(Request $request)
    {
        $claims = Claim::with(['listing.merchant'])
            ->where('user_id', $request->user()->id)
            ->where('status', ClaimStatus::DIAMBIL->value) // Filter hanya yang sudah selesai
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riwayat-pesanan', compact('claims'));
    }

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

    public function prosesTransaksi(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:midtrans',
        ]);

        DB::beginTransaction();

        try {
            $listing = Listing::lockForUpdate()->findOrFail($request->listing_id);

            if ($listing->stok_sisa < $request->jumlah) {
                throw new Exception('Stok sisa tidak mencukupi.');
            }

            $hargaSatuan = $listing->harga_diskon ?? 0;
            $subtotal = $hargaSatuan * $request->jumlah;
            $pajak = 2000; // Sesuai view
            $totalHarga = $subtotal + $pajak;

            $kodeKlaim = 'CLM-' . strtoupper(Str::random(8));

            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'listing_id' => $listing->id,
                'jumlah' => $request->jumlah,
                'total_harga' => $totalHarga,
                'kode_klaim' => $kodeKlaim,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => PaymentStatus::BELUM_DIBAYAR->value,
                'status' => ClaimStatus::PENDING->value,
            ]);

            $listing->decrement('stok_sisa', $request->jumlah);

            // Midtrans Logic
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $kodeKlaim,
                    'gross_amount' => (int)$totalHarga,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
                'callbacks' => [
                    'finish' => route('pesanan.detail', ['id' => $claim->id])
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $claim->update(['midtrans_snap_token' => $snapToken]);

            DB::commit();
            return response()->json(['status' => 'midtrans', 'snap_token' => $snapToken]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function konfirmasiPembayaran(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant || $merchant->status_verifikasi !== 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Aksi ditolak. Akun Merchant tidak valid.',
            ], 403);
        }

        $claim = Claim::where('id', $id)->whereHas('listing', function($query) use ($merchant) {
            $query->where('merchant_id', $merchant->id);
        })->firstOrFail();

        if ($claim->metode_pembayaran !== 'cash') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya pembayaran tunai yang dapat dikonfirmasi.',
            ], 400);
        }

        if ($claim->status_pembayaran === PaymentStatus::SUDAH_DIBAYAR->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran sudah dikonfirmasi sebelumnya.',
            ], 400);
        }

        $claim->update(['status_pembayaran' => PaymentStatus::SUDAH_DIBAYAR->value]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil dikonfirmasi.',
        ], 200);
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

    // ============================================================
    // ===================  WEB (BLADE) METHODS  ===================
    // ============================================================

    /**
     * Render halaman scan/verifikasi pesanan (scan QR atau input kode manual)
     */
    public function scanForm()
    {
        return view('merchant.scan-qr');
    }

    /**
     * Proses verifikasi pesanan dari Blade — dipakai baik untuk scan QR
     * (kode_klaim dikirim otomatis via JS hasil scan) maupun input manual.
     * Endpoint sama persis untuk dua mode, karena keduanya cuma kirim 'kode_klaim'.
     */
    public function verifikasiWeb(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant || $merchant->status_verifikasi !== 'disetujui') {
            return redirect()->back()->with('error', 'Akun Merchant Anda belum diverifikasi.');
        }

        $request->validate([
            'kode_klaim' => 'required|string',
        ]);

        $claim = Claim::with('listing.merchant')
            ->where('kode_klaim', trim($request->kode_klaim))
            ->first();

        if (! $claim) {
            return redirect()->back()->with('error', 'Kode tidak ditemukan. Pastikan kode yang diinput benar.')->withInput();
        }

        if ($claim->listing->merchant_id !== $merchant->id) {
            return redirect()->back()->with('error', 'Pesanan ini bukan untuk toko Anda.')->withInput();
        }

        if ($claim->status_pembayaran !== 'sudah_dibayar') {
            return redirect()->back()->with('error', 'Konsumen belum menyelesaikan pembayaran untuk pesanan ini.')->withInput();
        }

        if ($claim->status === 'diambil') {
            return redirect()->back()->with('error', 'Kode ini sudah pernah digunakan (pesanan sudah selesai).')->withInput();
        }

        $claim->update(['status' => 'diambil']);

        NotificationService::pesananSelesai($claim->user_id, $claim->id, $claim->listing->nama);

        return redirect()->route('merchant.scan-qr')->with('success',
            'Berhasil! Pesanan "'.$claim->listing->nama.'" ('.$claim->jumlah.'x) atas nama '.($claim->user->name ?? 'konsumen').' telah selesai diserahkan.'
        );
    }
}
