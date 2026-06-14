<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;

class MerchantDashboardController extends Controller
{
    // Statistik penjualan + makanan terselamatkan
    public function statistik(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil merchant tidak ditemukan.',
            ], 404);
        }

        $listingIds = Listing::where('merchant_id', $merchant->id)->pluck('id');

        $claimsValid = Claim::whereIn('listing_id', $listingIds)
            ->where('status', '!=', 'batal');

        // Total porsi terjual & total pendapatan (hanya yang sudah dibayar)
        $totalPorsiTerjual = (clone $claimsValid)->sum('jumlah');
        $totalPendapatan = (clone $claimsValid)
            ->where('status_pembayaran', 'sudah_dibayar')
            ->sum('total_harga');
        
        // Jumlah pembeli unik
        $totalPembeliUnik = (clone $claimsValid)->distinct('user_id')->count('user_id');

        // Makanan terselamatkan = total porsi yang berhasil diklaim (tidak batal)
        $makananTerselamatkan = $totalPorsiTerjual;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_porsi_terjual' => (int) $totalPorsiTerjual,
                'total_pendapatan' => (float) $totalPendapatan,
                'total_pembeli_unik' => $totalPembeliUnik,
                'makanan_terselamatkan' => (int) $makananTerselamatkan,
            ]
        ], 200);
    }

    // Daftar klaim masuk untuk listing milik merchant
    public function klaimMasuk(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil merchant tidak ditemukan.',
            ], 404);
        }

        $listingIds = Listing::where('merchant_id', $merchant->id)->pluck('id');

        $query = Claim::with(['user:id,name,username', 'listing:id,nama'])
            ->whereIn('listing_id', $listingIds)
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan listing terbaru
        if ($request->filled('listing_id')) {
            $query->where('listing_id', $request->listing_id);
        }

        // Filter berdasarkan status klaim
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $klaims = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $klaims,
        ], 200);
    }
}
