<?php

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use App\Helpers\GeoHelper; // Pastikan helper ini tetap ada
use App\Models\Listing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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

        if ($request->input('filter') !== 'terdekat') {
            match ($request->input('filter')) {
                'termurah' => $query->orderBy('harga_diskon', 'asc'),
                'terbaru' => $query->orderBy('created_at', 'desc'),
                default => $query->orderBy('created_at', 'desc'),
            };
        }

        $listings = $query->get();

        $userLat = $request->input('lat');
        $userLng = $request->input('lng');

        if (! $userLat && ! $userLng && $request->user()) {
            $profil = $request->user()->profil;
            if ($profil && $profil->izin_lokasi) {
                $userLat = $profil->latitude;
                $userLng = $profil->longitude;
            }
        }

        // PENGAMAN BERLAPIS UNTUK PERHITUNGAN JARAK DI HALAMAN UTAMA
        if ($userLat && $userLng) {
            $listings = $listings->map(function ($listing) use ($userLat, $userLng) {
                $listing->jarak_km = null; // Default awal jika gagal

                if (
                    $listing->merchant &&
                    is_numeric($listing->merchant->latitude) &&
                    is_numeric($listing->merchant->longitude) &&
                    is_numeric($userLat) &&
                    is_numeric($userLng)
                ) {
                    try {
                        $listing->jarak_km = GeoHelper::jarakKm(
                            (float) $userLat,
                            (float) $userLng,
                            (float) $listing->merchant->latitude,
                            (float) $listing->merchant->longitude
                        );
                    } catch (\Exception $e) {
                        // Jika GeoHelper crash karena kalkulasi matematika, amankan ke null
                        $listing->jarak_km = null;
                    }
                }

                return $listing;
            });

            if ($request->input('filter') === 'terdekat') {
                $listings = $listings->sortBy('jarak_km')->values();
            }
        }

        $perPage = 20;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $currentItems = $listings->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $listings = new LengthAwarePaginator(
            $currentItems,
            $listings->count(),
            $perPage,
            $currentPage,
            [
                'path' => Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Daftar makanan yang tersedia berhasil diambil',
                'data' => $listings,
            ], 200);
        }

        return view('listing-makanan', compact('listings'));
    }

    public function checkout(Request $request, $id)
    {
        // dd($id) SUDAH DIHAPUS AGAR PROSES JALAN TERUS
        $listing = Listing::with('merchant', 'kategori')->findOrFail($id);

        $userLat = $request->input('lat');
        $userLng = $request->input('lng');

        if (! $userLat && ! $userLng && $request->user()) {
            $profil = $request->user()->profil;
            if ($profil && $profil->izin_lokasi) {
                $userLat = $profil->latitude;
                $userLng = $profil->longitude;
            }
        }

        // PENGAMAN BERLAPIS UNTUK PERHITUNGAN JARAK DI HALAMAN CHECKOUT
        $listing->jarak_km = null;
        if (
            $userLat && $userLng &&
            $listing->merchant &&
            is_numeric($listing->merchant->latitude) &&
            is_numeric($listing->merchant->longitude) &&
            is_numeric($userLat) &&
            is_numeric($userLng)
        ) {
            try {
                $listing->jarak_km = GeoHelper::jarakKm(
                    (float) $userLat,
                    (float) $userLng,
                    (float) $listing->merchant->latitude,
                    (float) $listing->merchant->longitude
                );
            } catch (\Exception $e) {
                $listing->jarak_km = null;
            }
        }

        return view('checkout', compact('listing'));
    }

    public function show($id)
    {
        $listing = Listing::with(['merchant', 'kategori'])
            ->whereIn('status', ['aktif', 'hampir_habis'])
            ->where('stok_sisa', '>', 0)
            ->where('batas_waktu', '>', now())
            ->findOrFail($id);

        return view('listing-detail', compact('listing'));
    }
}
