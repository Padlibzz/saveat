<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // <-- Tambahkan ini untuk generate random string

class ClaimController extends Controller
{
    // ... method index() biarkan sama ...

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_listings' => 'required|exists:listings,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $listings = Listing::lockForUpdate()->find($request->id_listings);
            
            if (now()->greaterThan($listings->batas_waktu)) {
                return response()->json(['status' => 'error', 'message' => 'Gagal klaim: Makanan sudah melewati batas waktu pengambilan.'], 400);
            }

            if ($listings->stok_sisa < $request->jumlah) {
                return response()->json(['status' => 'error', 'message' => 'Gagal klaim: Stok sisa tidak mencukupi.'], 400);
            }

            // PERBAIKAN: Hitung total harga (asumsi ada kolom harga_diskon di tabel listings)
            // Jika klaim gratis, pastikan harga_diskon bernilai 0 di tabel listings
            $total_harga = $listings->harga_diskon * $request->jumlah;
            
            // PERBAIKAN: Generate kode klaim unik
            $kode_klaim = 'CLM-' . strtoupper(Str::random(6));

            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'id_listings' => $request->id_listings,
                'jumlah' => $request->jumlah,
                'total_harga' => $total_harga,   // <-- Dimasukkan agar tidak error
                'kode_klaim' => $kode_klaim,     // <-- Dimasukkan agar tidak error
                'status' => 'pending'            // <-- Diselaraskan dengan migration enum
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

        // PERBAIKAN: Selaraskan dengan enum migration (menggunakan 'diambil', bukan 'selesai')
        $claim->update(['status' => 'diambil']);

        return response()->json([
            'status' => 'success',
            'message' => 'Klaim berhasil diselesaikan (makanan telah diambil)',
            'data' => $claim
        ], 200);
    }
}