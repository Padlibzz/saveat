<?php

namespace App\Http\Controllers;

use App\Models\Category; 

class CategoryController extends Controller
{
    /**
     * Endpoint Publik: Menampilkan semua master data kategori pangan
     */
    public function index()
    {
        $categories = Category::orderBy('nama', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar kategori berhasil diambil.',
            'data' => $categories,
        ], 200);
    }
}
