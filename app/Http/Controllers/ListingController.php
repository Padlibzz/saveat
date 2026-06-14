<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::where('stok_sisa', '>', 0)
            ->whereIn('status', ['aktif', 'hampir_habis'])
            ->where('batas_waktu', '>', Carbon::now())
            ->with('merchant', 'kategori');

        // Pencarian nama makanan
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter urutan - termurah / terbaru
        match ($request->input('filter')) {
            'termurah' => $query->orderBy('harga_diskon', 'asc'),
            'terbaru' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $listings = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar makanan yang tersedia berhasil diambil',
            'data'    => $listings
        ], 200);
    }
}