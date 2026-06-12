<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            // lockForUpdate mencegah double claim oleh user berbeda di detik yang sama
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

            // Menyimpan riwayat klaim
            $claim = Claim::create([
                'user_id' => $request->user()->id,
                'id_listings' => $request->id_listings,
                'jumlah' => $request->jumlah,
                'status' => 'diproses' 
            ]);

            // Mengurangi stok secara otomatis
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

        // Hanya pemilik klaim atau admin/merchant yang boleh menyelesaikan
        if ($claim->user_id !== $request->user()->id && $request->user()->peran !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses.'], 403);
        }

        $claim->update(['status' => 'selesai']);

        return response()->json([
            'status' => 'success',
            'message' => 'Klaim berhasil diselesaikan',
            'data' => $claim
        ], 200);
    }
}