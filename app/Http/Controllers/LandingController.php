<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Claim;
use App\Models\Profil;
use App\Models\Review;

class LandingController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['merchant', 'kategori'])
            ->where('stok_sisa', '>', 0)
            ->where('batas_waktu', '>', now())
            ->whereIn('status', ['aktif', 'hampir_habis'])
            ->latest()
            ->take(4)
            ->get();

        // Total makanan yang benar-benar terselamatkan
        $makananTerselamatkan = Claim::where('status', 'diambil')
            ->sum('jumlah');

        // Merchant aktif & terverifikasi
        $jumlahMerchant = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'disetujui')
            ->count();

        // Rating rata-rata pengguna
        $ratingPengguna = round(
            Review::avg('rating') ?? 0,
            1
        );

        return view('landing', compact(
            'listings',
            'makananTerselamatkan',
            'jumlahMerchant',
            'ratingPengguna'
        ));
    }
}