<?php

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use App\Helpers\GeoHelper;
use App\Models\Listing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::where('stok_sisa', '>', 0)
            ->whereIn('status', [ListingStatus::AKTIF->value, ListingStatus::HAMPIR_HABIS->value])
            ->where('batas_waktu', '>', Carbon::now())
            ->with('merchant', 'kategori');

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $query->where('nama', 'like', '%'.$search.'%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        match ($request->input('filter')) {
            'termurah' => $query->orderBy('harga_diskon', 'asc'),
            'terbaru' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $listings = $query->get();

        // Hitung jarak jika user mengirim koordinat atau memakai lokasi profil
        $userLat = $request->input('lat');
        $userLng = $request->input('lng');

        if (! $userLat && ! $userLng && $request->user()) {
            $profil = $request->user()->profil;

            if ($profil && $profil->izin_lokasi) {
                $userLat = $profil->latitude;
                $userLng = $profil->longitude;
            }
        }

        if ($userLat && $userLng) {
            $listings = $listings->map(function ($listing) use ($userLat, $userLng) {
                if (
                    $listing->merchant &&
                    $listing->merchant->latitude &&
                    $listing->merchant->longitude
                ) {
                    $listing->jarak_km = GeoHelper::jarakKm(
                        (float) $userLat,
                        (float) $userLng,
                        (float) $listing->merchant->latitude,
                        (float) $listing->merchant->longitude
                    );
                } else {
                    $listing->jarak_km = null;
                }

                return $listing;
            });

            if ($request->input('filter') === 'terdekat') {
                $listings = $listings->sortBy('jarak_km')->values();
            }
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Daftar makanan yang tersedia berhasil diambil',
                'data' => $listings,
            ], 200);
        }

        return view('listings.index', compact('listings'));
    }
}
