<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantListingController extends Controller
{
    public function index(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil merchant tidak ditemukan.',
            ], 404);
        }

        $listings = Listing::with('kategori')
            ->where('merchant_id', $merchant->id)
            ->where('merchant_id', $merchant->id) 
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $listings,
        ], 200);
    }

    public function store(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant || $merchant->status_verifikasi !== 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Aksi ditolak. Akun Merchant Anda belum diverifikasi oleh Admin.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            // PERBAIKAN: Ubah 'categoris' menjadi 'categories' agar sesuai nama tabel
            'kategori_id' => 'required|exists:categories,id',
            'kategori_id' => 'required|exists:categories,id', 
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lt:harga_normal',
            'stok_total' => 'required|integer|min:1',
            'batas_waktu' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $input = $request->all();

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $namaFoto = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('listings', $namaFoto, 'public');
                $input['foto'] = $path;
            }

            $input['merchant_id'] = $merchant->id;
            $input['merchant_id'] = $merchant->id; 
            $input['stok_sisa'] = $request->stok_total;
            $input['status'] = 'aktif';

            $listing = Listing::create($input);

            return response()->json([
                'status' => 'success',
                'message' => 'Listing makanan berhasil dibuat.',
                'data' => $listing,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: '.$e->getMessage(),
            ], 500);
        }
    }

    // public function dashboard(Request $request)
    // {
    //     $merchant = $request->user()->merchant;

    //     if (!merchant) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Profil merchant tidak ditemukan.'
    //         ], 404);
    //     }

    //     $totalTerselamatkan = Claim::whereHas('listing', function ($query) use ($merchant) {
    //         $query->where('id_merchant', $merchant->id);
    //     })
    //     ->where('status', 'diambil')
    //     ->sum('jumlah');

    //     $totalPendapatan = Claim::whereHas('listing', function ($query) use ($merchant) {
    //         $query->where('merchant_id', $merchant->id);
    //     })
    //     ->where('status_pembayaran', 'sudah_dibayar')
    //     ->sum('total_harga');

    //     $produkAktif = Listing::where('merchant_id', $merchant->id)
    //     ->where('status', 'aktif')
    //     ->where('stok_sisa', '>', 0)
    //     ->count();

    //     $transaksiTerbaru = Claim::with(['listing', 'user'])
    //         ->whereHas('listing', function ($query) use ($merchant) {
    //             $query->where('merchant_id', $merchant->id);
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => [
    //             'total_makanan_terselamatkan' => $totalTerselamatkan,
    //             'total_pendapatan' => (float) $totalPendapatan,
    //             'total_produk_aktif' => $produkAktif,
    //             'riwayat_transaksi_terbaru' => $transaksiTerbaru
    //         ]
    //     ], 200);
    // }
}
