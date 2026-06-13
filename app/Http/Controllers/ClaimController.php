<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $claims = Claim::with(['listing.merchant', 'listing.kategori'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $claims], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'listing_id' => 'required|exists:listings,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $listing = Listing::lockForUpdate()->find($request->listing_id);

            if (now()->greaterThan($listing->batas_waktu)) {
                return response()->json(['status' => 'error', 'message' => 'Gagal klaim: Makanan sudah melewati batas waktu pengambilan.'], 400);
            }

            if ($listing->stok_sisa < $request->jumlah) {
                return response()->json(['status' => 'error', 'message' => 'Gagal klaim: Stok sisa tidak mencukupi.'], 400);
            }

            $total_harga = ($listing->harga_diskon ?? 0) * $request->jumlah;
            $kode_klaim = 'CLM-' . strtoupper(Str::random(8));

            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'listing_id' => $request->listing_id, 
                'jumlah' => $request->jumlah,
                'total_harga' => $total_harga,
                'kode_klaim' => $kode_klaim,
                'status_pembayaran' => 'belum_dibayar',
                'status' => 'pending',
            ]);

            $listing->decrement('stok_sisa', $request->jumlah);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil membuat pesanan, silakan lanjutkan ke pembayaran.',
                'data' => $claim
            ], 201);
        });
    }

    public function bayar(Request $request, $id)
    {
        $claim = Claim::find($id);

        if (!$claim || $claim->user_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak valid.'], 404);
        }

        if ($claim->status_pembayaran === 'sudah_dibayar') {
            return response()->json(['status' => 'error', 'message' => 'Pesanan ini sudah dibayar.'], 400);
        }

        $request->validate([
            'metode_pembayaran' => 'required|string'
        ]);

        $claim->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'sudah_dibayar',
            'waktu_pembayaran' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil. Tunjukkan QR Code ini ke Merchant.',
            'qr_data' => $claim->kode_klaim, 
            'data' => $claim
        ], 200);
    }

    public function scanQr(Request $request)
    {
        $merchant = $request->user()->merchant;
        if (! $merchant || $merchant->status_verifikasi !== 'disetujui') {
            return response()->json(['status' => 'error', 'message' => 'Aksi ditolak. Akun Merchant tidak valid.'], 403);
        }

        $request->validate([
            'kode_klaim' => 'required|string'
        ]);

        $claim = Claim::with('listing')->where('kode_klaim', $request->kode_klaim)->first();

        if (!$claim) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak valid atau pesanan tidak ditemukan.'], 404);
        }

        if ($claim->listing->merchant_id !== $merchant->id) {
            return response()->json(['status' => 'error', 'message' => 'Ini bukan pesanan untuk toko Anda.'], 403);
        }

        if ($claim->status_pembayaran !== 'sudah_dibayar') {
            return response()->json(['status' => 'error', 'message' => 'Konsumen belum menyelesaikan pembayaran untuk pesanan ini.'], 400);
        }

        if ($claim->status === 'diambil') {
            return response()->json(['status' => 'error', 'message' => 'QR Code ini sudah pernah digunakan (Makanan sudah diambil).'], 400);
        }

        $claim->update(['status' => 'diambil']);

        return response()->json([
            'status' => 'success',
            'message' => 'Scan Berhasil! Pesanan atas nama konsumen telah selesai dan makanan bisa diserahkan.',
            'data' => $claim
        ], 200);
    }

    public function selesai(Request $request, $id)
    {
        $claim = Claim::find($id);

        if (!$claim || $claim->user_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak valid.'], 404);
        }

        // Memastikan hanya pesanan yang sudah dibayar/diproses yang bisa diselesaikan
        if ($claim->status === 'batal') {
            return response()->json(['status' => 'error', 'message' => 'Pesanan sudah dibatalkan.'], 400);
        }

        $claim->update(['status' => 'diambil']); // Sesuaikan string status dengan enum database Anda

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil diselesaikan.',
            'data' => $claim
        ], 200);
    }

    public function riwayat(Request $request)
    {
        $klaims = Claim::with(['listing:id,nama,foto,status,batas_waktu', 'listing.merchant:id,nama_usaha'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($klaim) {
                $klaim->status_riwayat = $this->mapStatusRiwayat($klaim);
            });

        return response()->json([
            'status' => 'success',
            'data' => $klaims,
        ], 200);
    }

    public function mapStatusRiwayat(Claim $klaim): string
    {
        if ($klaim->status === 'diambil') {
            return 'sudah_diambil';
        }

        if ($klaim->status === 'batal') {
            return 'kadaluarsa';
        }

        // status === 'pending' atau lainnya yang masih berjalan
        if ($klaim->listing && $klaim->listing->status === 'tutup') {
            return 'kadaluarsa';
        }

        return 'aktif';
    }
}