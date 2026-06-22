<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Claim;
use App\Models\Profil;

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
    }
}
