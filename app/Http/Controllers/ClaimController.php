<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClaimController extends Controller
{
    // Menampilkan riwayat klaim makanan milik konsumen yang sedang login
    public function index(Request $request)
    {
        $claims = Claim::with(['listings.merchant', 'listings.kategori'])
            ->where('id_pengguna', $request->pengguna()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $claims
        ], 200);
    }

    // Proses melakukan klaim makanan
    public function store(Request $request)
    {
        // Validasi input jumlah pesanan
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

        //Jika salah satu query gagal, database otomatis di rollback
        return DB::transaction(function () use ($request) {
            $listings = Listing::find($request->id_listings);
            
            // cek apakah makanan sudah melewati batas waktu pengambilan
            if (now()->greaterThan($listings->batas_waktu)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal klaim: Makanan sudah melewati batas waktu pengambilan.'
                ], 400);
            }

            // Cek kecukupan stok sisa
            if ($listings->stok_sisa < $request->jumlah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal klaim: Stok sisa tidak mencukupi. Sisa stok: ' . $listings->stok_sisa
                ], 400);
            }
        });
    }
}
