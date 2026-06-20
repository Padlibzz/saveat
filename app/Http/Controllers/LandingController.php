<?php

namespace App\Http\Controllers;

use App\Models\Listing;

class LandingController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['merchant', 'kategori'])
        ->where('status', 'aktif')
        ->take(4)
        ->get();

        return view('landing', compact('listings'));
    }
}