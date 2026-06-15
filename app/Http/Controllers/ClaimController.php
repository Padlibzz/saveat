<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClaimController extends Controller
{
    /**
     * Menampilkan riwayat klaim makanan milik konsumen yang sedang login.
     */
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
                // Pastikan fungsi helperresolveStatusRiwayat didefinisikan jika dipanggil
                $klaim->status_riwayat = method_exists($this, 'resolveStatusRiwayat') 
                    ? $this->resolveStatusRiwayat($klaim) 
                    : $klaim->status;
                return $klaim;
            });

        return response()->json(['status' => 'success', 'data' => $claims], 200);
    }

    /**
     * Mendapatkan daftar metode pembayaran yang didukung sistem (Sinkron dengan PaymentController).
     */
    public function paymentMethods()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                ['id' => 'qris', 'nama' => 'QRIS'],
                ['id' => 'gopay', 'nama' => 'GoPay'],
                ['id' => 'dana', 'nama' => 'DANA'],
                ['id' => 'ovo', 'nama' => 'OVO'],
                ['id' => 'shopeepay', 'nama' => 'ShopeePay'],
                ['id' => 'linkaja', 'nama' => 'LinkAja'],
                ['id' => 'transfer_bank', 'nama' => 'Transfer Bank (VA)'],
                ['id' => 'tunai', 'nama' => 'Tunai di Gerai Retail (Indomaret/Alfamart)'],
            ]
        ], 200);
    }

    /**
     * Membuat klaim makanan baru (Pesanan baru).
     * Menggunakan Pessimistic Locking (lockForUpdate) untuk menghindari race condition stok sisa.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'listing_id' => 'required|exists:listings,id',
            'jumlah'     => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $listing = Listing::lockForUpdate()->with('merchant')->find($request->listing_id);

            if (now()->greaterThan($listing->batas_waktu)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal klaim: Makanan sudah melewati batas waktu pengambilan.'
                ], 400);
            }

            if (!in_array($listing->status, ['aktif', 'hampir_habis'])) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal klaim: Listing ini sudah tidak tersedia.'
                ], 400);
            }

            if ($listing->stok_sisa < $request->jumlah) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal klaim: Stok sisa tidak mencukupi.'
                ], 400);
            }

            $claim = Claim::create([
                'user_id'           => $request->user()->id,
                'listing_id'        => $request->listing_id,
                'jumlah'            => $request->jumlah,
                'total_harga'       => ($listing->harga_diskon ?? 0) * $request->jumlah,
                'kode_klaim'        => 'CLM-' . strtoupper(Str::random(8)),
                'status_pembayaran' => 'belum_dibayar',
                'status'            => 'pending',
            ]);

            $listing->decrement('stok_sisa', $request->jumlah);

            // Perbarui status keter sediaan listing secara berkala
            $persenSisa = $listing->stok_sisa / max($listing->stok_total, 1);
            if ($listing->stok_sisa <= 0) {
                $listing->update(['status' => 'tutup']);
            } elseif ($listing->status === 'aktif' && $persenSisa < 0.2) {
                $listing->update(['status' => 'hampir_habis']);
            }

            // Kirim Notifikasi Transaksi Berhasil Dibuat ke Konsumen
            NotificationService::klaimBerhasil(
                $request->user()->id,
                $claim->id,
                $listing->nama,
                Carbon::parse($listing->batas_waktu)->format('H:i, d M Y')
            );

            // Kirim Notifikasi Klaim Masuk ke Pihak Merchant
            if ($listing->merchant && $listing->merchant->user_id) {
                NotificationService::klaimMasuk(
                    $listing->merchant->user_id,    
                    $claim->id,
                    $listing->nama,
                );
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Berhasil membuat pesanan, silakan lanjutkan ke pembayaran.',
                'data'    => $claim,
            ], 201);
        });
    }

    /**
     * Fitur Scan QR Code oleh Merchant untuk menyelesaikan pesanan konsumen di tempat (In-store settlement).
     */
    public function scanQr(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (!$merchant || $merchant->status_verifikasi !== 'disetujui') {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Aksi ditolak. Akun Merchant tidak valid.'
            ], 403);
        }

        $request->validate(['kode_klaim' => 'required|string']);

        $claim = Claim::with('listing')->where('kode_klaim', $request->kode_klaim)->first();

        if (!$claim) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'QR Code tidak valid atau pesanan tidak ditemukan.'
            ], 404);
        }

        if ($claim->listing->merchant_id !== $merchant->id) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Ini bukan pesanan untuk toko Anda.'
            ], 403);
        }

        if ($claim->status_pembayaran !== 'sudah_dibayar') {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Konsumen belum menyelesaikan pembayaran.'
            ], 400);
        }

        if ($claim->status === 'diambil') {
            return response()->json([
                'status'    => 'error', 
                'message'   => 'QR Code ini sudah pernah digunakan.'
            ], 400);
        }

        $claim->update(['status' => 'diambil']);

        // Kirim Notifikasi kepada Konsumen bahwa Makanan telah Berhasil Diambil
        NotificationService::pesananSelesai($claim->user_id, $claim->id, $claim->listing->nama);

        return response()->json([
            'status'  => 'success',
            'message' => 'Scan berhasil! Makanan bisa diserahkan ke konsumen.',
            'data'    => $claim,
        ], 200);
    }

    /**
     * Konfirmasi manual pesanan selesai oleh pengguna aplikasi.
     */
    public function selesai(Request $request, $id)
    {
        $claim = Claim::find($id);

        if (!$claim || $claim->user_id !== $request->user()->id) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Pesanan tidak valid.'
            ], 404);
        }

        if ($claim->status === 'batal') {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Pesanan sudah dibatalkan.'
            ], 400);
        }

        if ($claim->status === 'diambil') {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Pesanan sudah diselesaikan sebelumnya.'
            ], 400);
        }

        $claim->update(['status' => 'diambil']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pesanan berhasil diselesaikan.',
            'data'    => $claim,
        ], 200);
    }
}