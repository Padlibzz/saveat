<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantListingController extends Controller
{
    // Menampilkan semua daftar listing makanan milik merchant yang sedang login
    public function index(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (!$merchant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil merchant tidak ditemukan.'
            ], 404);
        }

        //Ambil data listing beserta nama kategorinya
        $listings = Listing::with('categori')
            ->where('id_merchant', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $listings
        ], 200);
    }

    // Membuat Listing Makanan Baru oleh Merchant
    public function store(Request $request)
    {
        $merchant = $request->user()->merchant;

        // Validasi hak akses merchant
        if (!$merchant || $merchant->status_verifikasi !== 'approved') {
            return response()->json([
                'status' => 'error',
                'message' => 'Aksi ditolak. Akun Merchant Anda belum diverifikasi oleh Admin.'
            ], 403);
        }

        // Validasi input data dari frontend\
        $validator = Validator::make($request->all(), [
            'id_categori' => 'required|exists:categoris,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lt:harga_normal', // Harga diskon harus lebih kecil dari harga normal
            'stok_total' => 'required|integer|min:1',
            'batas_waktu' => 'required|date|after:now', // Batas pengambilan harus di masa depan
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $input = $request->all();

            //Proses upload foto jika ada
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $namaFoto = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/listings'), $namaFoto);
                $input['foto'] = 'listings/' . $namaFoto;
            }

            // Set data otomatis sebelum masuk database
            $input['id_merchant'] = $merchant->id;
            $input['stok_sisa'] = $request->stok_total;
            $input['status'] = 'aktif';

            $listing = Listing::create($input);

            return response()->json([
                'status' => 'success',
                'message' => 'Listing makanan berhasil dibuat.',
                'data' => $listing
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
