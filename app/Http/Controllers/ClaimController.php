<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use App\Enums\ClaimStatus;
use App\Enums\ListingStatus;
use App\Enums\PaymentStatus;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        // PERBAIKAN: Menggunakan Enum
        if ($klaim->status === ClaimStatus::DIAMBIL->value) return 'sudah_diambil';
        if ($klaim->status === ClaimStatus::BATAL->value) return 'kadaluarsa';
        if ($klaim->listing && $klaim->listing->status === ListingStatus::TUTUP->value) return 'kadaluarsa';
        return 'aktif';
    }

    public function paymentMethods()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                ['id' => 'qris',          'nama' => 'QRIS',               'kategori' => 'qr_code'],
                ['id' => 'gopay',         'nama' => 'GoPay',              'kategori' => 'e_wallet'],
                ['id' => 'dana',          'nama' => 'DANA',               'kategori' => 'e_wallet'],
                ['id' => 'ovo',           'nama' => 'OVO',                'kategori' => 'e_wallet'],
                ['id' => 'shopeepay',     'nama' => 'ShopeePay',          'kategori' => 'e_wallet'],
                ['id' => 'linkaja',       'nama' => 'LinkAja',            'kategori' => 'e_wallet'],
                ['id' => 'transfer_bank', 'nama' => 'Transfer Bank (VA)', 'kategori' => 'bank'],
                ['id' => 'tunai',         'nama' => 'Tunai di Gerai',     'kategori' => 'cash'],
            ]
        ], 200);
    }

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

            // PERBAIKAN: Menggunakan Enum
            if (!in_array($listing->status, [ListingStatus::AKTIF->value, ListingStatus::HAMPIR_HABIS->value])) {
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

            // PERBAIKAN: Menggunakan Enum
            $claim = Claim::create([
                'user_id'           => $request->user()->id,
                'listing_id'        => $request->listing_id,
                'jumlah'            => $request->jumlah,
                'total_harga'       => ($listing->harga_diskon ?? 0) * $request->jumlah,
                'kode_klaim'        => 'CLM-' . strtoupper(Str::random(8)),
                'status_pembayaran' => PaymentStatus::BELUM_DIBAYAR->value,
                'status'            => ClaimStatus::PENDING->value,
            ]);

            $listing->decrement('stok_sisa', $request->jumlah);

            $persenSisa = $listing->stok_sisa / max($listing->stok_total, 1);
            if ($listing->stok_sisa <= 0) {
                $listing->update(['status' => ListingStatus::TUTUP->value]);
            } elseif ($listing->status === ListingStatus::AKTIF->value && $persenSisa < 0.2) {
                $listing->update(['status' => ListingStatus::HAMPIR_HABIS->value]);
            }

            NotificationService::menungguPembayaran(
                $request->user()->id,
                $claim->id,
                $listing->nama
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Berhasil membuat pesanan, silakan lanjutkan ke pembayaran.',
                'data'    => $claim,
            ], 201);
        });
    }

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

        // PERBAIKAN: Menggunakan Enum
        if ($claim->status_pembayaran !== PaymentStatus::SUDAH_DIBAYAR->value) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Konsumen belum menyelesaikan pembayaran.'
            ], 400);
        }

        if ($claim->status === ClaimStatus::DIAMBIL->value) {
            return response()->json([
                'status'    => 'error', 
                'message'   => 'QR Code ini sudah pernah digunakan.'
            ], 400);
        }

        $claim->update(['status' => ClaimStatus::DIAMBIL->value]);

        NotificationService::pesananSelesai($claim->user_id, $claim->id, $claim->listing->nama);

        return response()->json([
            'status'  => 'success',
            'message' => 'Scan berhasil! Makanan bisa diserahkan ke konsumen.',
            'data'    => $claim,
        ], 200);
    }

    public function selesai(Request $request, $id)
    {
        $claim = Claim::find($id);

        if (!$claim || $claim->user_id !== $request->user()->id) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Pesanan tidak valid.'
            ], 404);
        }

        // PERBAIKAN: Menggunakan Enum
        if ($claim->status === ClaimStatus::BATAL->value) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Pesanan sudah dibatalkan.'
            ], 400);
        }

        if ($claim->status === ClaimStatus::DIAMBIL->value) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Pesanan sudah diselesaikan sebelumnya.'
            ], 400);
        }

        $claim->update(['status' => ClaimStatus::DIAMBIL->value]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pesanan berhasil diselesaikan.',
            'data'    => $claim,
        ], 200);
    }
}