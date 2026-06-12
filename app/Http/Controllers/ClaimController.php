<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        // UBAH: relasi dipanggil dengan nama 'listing' bukan 'listings'
        $claims = Claim::with(['listing.merchant', 'listing.kategori'])
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
        // UBAH: validasi menggunakan listing_id
        $validator = Validator::make($request->all(), [
            'listing_id' => 'required|exists:listings,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            // UBAH: pencarian menggunakan listing_id
            $listing = Listing::lockForUpdate()->find($request->listing_id);
            
            if (now()->greaterThan($listing->batas_waktu)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal klaim: Makanan sudah melewati batas waktu pengambilan.'
                ], 400);
            }

            if ($listing->stok_sisa < $request->jumlah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal klaim: Stok sisa tidak mencukupi. Sisa stok: ' . $listing->stok_sisa
                ], 400);
            }

            $total_harga = ($listing->harga_diskon ?? 0) * $request->jumlah;
            $kode_klaim = 'CLM-' . strtoupper(Str::random(6));

            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'listing_id' => $request->listing_id, // UBAH ke listing_id
                'jumlah' => $request->jumlah,
                'total_harga' => $total_harga,
                'kode_klaim' => $kode_klaim,
                'status' => 'pending' 
            ]);

            $listing->decrement('stok_sisa', $request->jumlah);

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

        $claim->update(['status' => 'diambil']);

        return response()->json([
            'status' => 'success',
            'message' => 'Klaim berhasil diselesaikan',
            'data' => $claim
        ], 200);
    }
}