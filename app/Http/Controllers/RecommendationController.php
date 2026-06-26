<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Enums\ListingStatus;
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
        $listings = $this->getRecommendations($request->user(), 6, $request->search);

        return view('customer.dashboard', compact('listings'));
    }

    private function getRecommendations($user, $limit = 20, $search = null)
    {
        $profil = $user->profil;

        $kategoriFavorit = Claim::where('user_id', $user->id)
            ->where('claims.status', '!=', ClaimStatus::BATAL->value)
            ->join('listings', 'claims.listing_id', '=', 'listings.id')
            ->selectRaw('listings.kategori_id, COUNT(*) as total')
            ->groupBy('listings.kategori_id')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('listings.kategori_id');

        $query = Listing::where('stok_sisa', '>', 0)
            ->whereIn('status', [ListingStatus::AKTIF->value, ListingStatus::HAMPIR_HABIS->value])
            ->where('batas_waktu', '>', now())
            ->with(['merchant', 'kategori']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhereHas('merchant', function ($m) use ($search) {
                      $m->where('nama_usaha', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($kategoriFavorit->isNotEmpty()) {
            $query->orderByRaw(
                'FIELD(kategori_id, '.$kategoriFavorit->reverse()->implode(',').') DESC'
            );
        }

        // Prioritaskan yang hampir habis (urgensi food waste)
        $query->orderByRaw("FIELD(status, '".ListingStatus::HAMPIR_HABIS->value."', '".ListingStatus::AKTIF->value."') ASC");
        
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
