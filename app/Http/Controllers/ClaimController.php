<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // <-- Perbaikan: Diperlukan untuk Str::random()

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $claims = Claim::with(['listings.merchant', 'listings.kategori'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $claims
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_listings' => 'required|exists:listings,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            $listings = Listing::lockForUpdate()->find($request->id_listings);
            
            if (now()->greaterThan($listings->batas_waktu)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal klaim: Makanan sudah melewati batas waktu pengambilan.'
                ], 400);
            }

            if ($listings->stok_sisa < $request->jumlah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal klaim: Stok sisa tidak mencukupi. Sisa stok: ' . $listings->stok_sisa
                ], 400);
            }

            // PERBAIKAN: Hitung total harga (pastikan di database listing ada harga_diskon, jika tidak akan dianggap 0)
            $total_harga = ($listings->harga_diskon ?? 0) * $request->jumlah;
            
            // PERBAIKAN: Generate kode klaim unik
            $kode_klaim = 'CLM-' . strtoupper(Str::random(6));

            // PERBAIKAN: Masukkan semua data wajib dan sesuaikan status dengan migration
            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'id_listings' => $request->id_listings,
                'jumlah' => $request->jumlah,
                'total_harga' => $total_harga,
                'kode_klaim' => $kode_klaim,
                'status' => 'pending' // Sebelumnya 'diproses', ini akan error karena di migration hanya boleh 'pending', 'diambil', 'batal'
            ]);

            $listings->decrement('stok_sisa', $request->jumlah);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil melakukan klaim',
                'data' => $claim
            ], 201);
        });
    }

    public function selesai(Request $request, $id)
    {
        $claim = Claim::find($id);

        if (!$claim) {
            return response()->json(['status' => 'error', 'message' => 'Data klaim tidak ditemukan'], 404);
        }

        if ($claim->user_id !== $request->user()->id && $request->user()->peran !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses.'], 403);
        }

        // PERBAIKAN: Sesuaikan dengan status enum di migration ('diambil' bukan 'selesai')
        $claim->update(['status' => 'diambil']);

        return response()->json([
            'status' => 'success',
            'message' => 'Klaim berhasil diselesaikan',
            'data' => $claim
        ], 200);
    }
}