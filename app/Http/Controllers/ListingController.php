<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ListingController extends Controller
{
    /**
     * Menampilkan daftar makanan yang tersedia untuk konsumen.
     */
    public function index()
    {
        // Mengambil data makanan sesuai dengan syarat yang ditentukan
        $listings = Listing::where('stok_sisa', '>', 0)
            ->where('status', 'aktif')
            ->where('batas_waktu', '>', Carbon::now())
            ->with('merchant') // Opsional: jika ingin menampilkan data merchant terkait (pastikan relasi sudah ada di model Listing)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar makanan yang tersedia berhasil diambil',
            'data'    => $listings
        ], 200);
    }
}