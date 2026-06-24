<?php

namespace App\Http\Controllers;

use App\Models\Listing;

class LandingController extends Controller
{
    public function index()
    {
        // Tampilkan listing terbaru yang masih tersedia (4 item untuk landing page)
        $listings = Listing::whereIn('status', ['aktif', 'hampir_habis'])
            ->where('stok_sisa', '>', 0)
            ->where('batas_waktu', '>', now())
            ->with(['merchant', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Statistik real-time untuk landing page
        $stats = app(PublicStatsController::class)
            ->index()
            ->getData()
            ->data;

        return view('main.landing', compact('listings', 'stats'));
    }
}
