<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Helpers\GeoHelper;
use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Rekomendasi gabungan riwayat klaim (kategori favorit) + jarak + hampir habis
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $profil = $user->profil;

        // 1. Cari kategori favorit dari riwayat klaim (3 kategori teratas)
        $kategoriFavorit = Claim::where('user_id', $user->id)
            ->where('status', '!=', ClaimStatus::BATAL->value)
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

        // Jika user punya riwayat, prioritaskan kategori favorit
        if ($kategoriFavorit->isNotEmpty()) {
            $query->orderByRaw(
                'FIELD(kategori_id, '.$kategoriFavorit->reverse()->implode(',').') DESC'
            );
        }

        // Prioritaskan yang hampir habis (urgensi food waste)
        $query->orderByRaw("FIELD(status, '".ListingStatus::HAMPIR_HABIS->value."', '".ListingStatus::AKTIF->value."') ASC");
        $listings = $query->limit(20)->get();

        // 2. Hitung jarak jika user aktifkan lokasi, lalu re-sort dalam radius 5km dulu
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
                // listing dalam 5km diprioritaskan, tapi urutan kategori favorit & hampir_habis tetap dominan
                return $listing->jarak_km !== null && $listing->jarak_km <= 5 ? 0 : 1;
            })->values();
        }

        return response()->json([
            'status' => 'success',
            'message' => $kategoriFavorit->isNotEmpty()
                ? 'Rekomendasi berdasarkan kebiasaanmu.'
                : 'Rekomendasi makanan hampir habis di dekatmu.',
            'data' => $listings,
        ], 200);
    }
}
