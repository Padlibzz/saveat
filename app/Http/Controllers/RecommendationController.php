<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $listings = $this->getRecommendations($request->user(), 20);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rekomendasi makanan',
            'data'    => $listings,
        ]);
    }

    public function dashboard(Request $request)
    {
        $listings = $this->getRecommendations($request->user(), 4);

        return view('dashboard-user', compact('listings'));
    }

    private function getRecommendations($user, $limit = 20)
    {
        $profil = $user->profil;

        $kategoriFavorit = Claim::where('user_id', $user->id)
            ->where('claims.status', '!=', 'batal')
            ->join('listings', 'claims.listing_id', '=', 'listings.id')
            ->selectRaw('listings.kategori_id, COUNT(*) as total')
            ->groupBy('listings.kategori_id')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('listings.kategori_id');

        $query = Listing::where('stok_sisa', '>', 0)
            ->whereIn('status', ['aktif', 'hampir_habis'])
            ->where('batas_waktu', '>', now())
            ->with(['merchant', 'kategori']);

        if ($kategoriFavorit->isNotEmpty()) {
            $query->orderByRaw(
                'FIELD(kategori_id, ' . $kategoriFavorit->implode(',') . ') DESC'
            );
        }

        $query->orderByRaw("FIELD(status, 'hampir_habis', 'aktif') ASC");

        $listings = $query->limit(20)->get();

        if ($profil && $profil->izin_lokasi && $profil->latitude && $profil->longitude) {
            $listings = $listings->map(function ($listing) use ($profil) {
                if ($listing->merchant && $listing->merchant->latitude && $listing->merchant->longitude) {
                    $listing->jarak_km = GeoHelper::jarakKm(
                        (float) $profil->latitude,
                        (float) $profil->longitude,
                        (float) $listing->merchant->latitude,
                        (float) $listing->merchant->longitude
                    );
                } else {
                    $listing->jarak_km = null;
                }
                return $listing;
            })->sortBy(function ($listing) {
                return $listing->jarak_km !== null && $listing->jarak_km <= 5 ? 0 : 1;
            })->values();
        }

        return $listings->take($limit);
    }
}