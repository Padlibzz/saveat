<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Profil;
use App\Models\Review;

class PublicStatsController extends Controller
{
    /**
     * Statistik publik untuk landing page:
     * - Total makanan terselamatkan
     * - Total merchant terdaftar (terverifikasi)
     * - Rating rata-rata pengguna
     */
    public function index()
    {
        $makananTerselamatkan = Claim::where('status', '!=', 'batal')->sum('jumlah');

        $totalMerchant = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'disetujui')
            ->count();

        $ratingRataRata = Review::avg('rating');
        $totalReview = Review::count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'makanan_terselamatkan' => (int) $makananTerselamatkan,
                'total_merchant'        => $totalMerchant,
                'rating_pengguna'       => $ratingRataRata ? round($ratingRataRata, 1) : 0,
                'total_review'          => $totalReview,
            ],
        ], 200);
    }
}