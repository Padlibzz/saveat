<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'kategori_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_normal',
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

    public function update(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        $listing = Listing::where('id', $id)
            ->where('merchant_id', $merchant->id)
            ->first();

        if (! $listing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing tidak ditemukan atau bukan milik Anda.',
            ], 404);
        }

        if (! in_array($listing->status, ['aktif', 'hampir_habis'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing yang sudah tutup/diarsipkan tidak dapat diedit.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'sometimes|numeric|min:0',
            'harga_diskon' => [
                'sometimes',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $listing) {
                    $hargaNormal = $request->input('harga_normal', $listing->harga_normal);
                    if ($value > $hargaNormal) {
                        $fail('Harga diskon tidak boleh lebih besar dari harga normal.');
                    }
                },
            ],
            'stok_total' => 'sometimes|integer|min:1',
            'batas_waktu' => 'sometimes|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $input = $validator->validated();

        if ($request->filled('stok_total')) {
            $terjual = $listing->stok_total - $listing->stok_sisa;
            $stokBaru = $request->stok_total - $terjual;

            if ($stokBaru < 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok total baru tidak boleh kurang dari jumlah porsi yang sudah terklaim ('.$terjual.').',
                ], 400);
            }

            $input['stok_sisa'] = $stokBaru;
        }

        if ($request->hasFile('foto')) {
            if ($listing->foto && Storage::disk('public')->exists($listing->foto)) {
                Storage::disk('public')->delete($listing->foto);
            }

            $file = $request->file('foto');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('listings', $namaFoto, 'public');
            $input['foto'] = $path;
        }

        $listing->update($input);

        return response()->json([
            'status' => 'success',
            'message' => 'Listing berhasil diperbarui.',
            'data' => $listing,
        ], 200);
    }

    public function tutup(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        $listing = Listing::where('id', $id)
            ->where('merchant_id', $merchant->id)
            ->first();

        if (! $listing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing tidak ditemukan atau bukan milik Anda.',
            ], 404);
        }

        if ($listing->status === 'tutup') {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing ini sudah ditutup.',
            ], 400);
        }

        $listing->update(['status' => 'tutup']);

        return response()->json([
            'status' => 'success',
            'message' => 'Listing berhasil ditutup.',
            'data' => $listing,
        ], 200);
    }
}
