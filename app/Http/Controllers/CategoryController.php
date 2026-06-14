<?php

namespace App\Http\Controllers;

use App\Models\Category; // Pastikan model ini sudah ada
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Endpoint Publik: Menampilkan semua master data kategori pangan
     */
    public function index()
    {
        // Mengambil semua data kategori, diurutkan berdasarkan nama (opsional)
        $categories = Category::orderBy('nama', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar kategori berhasil diambil.',
            'data' => $categories
        ], 200);
    }
}