<?php

namespace App\Http\Controllers;

use App\Models\Listing;

class LandingController extends Controller
{
    public function index()
    {
        $listings = Listing::orderBy('jarak')
            ->take(4)
            ->get();

        return view('landing', compact('listings'));
    }
}