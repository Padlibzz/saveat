<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $query = Profil::with('pengguna');

        if ($request->has('tipe')) {
            $query->where('tipe_profil', $request->tipe);
        }

        $profils = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $profils
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_pengguna' => 'required|exists:pengguna,id', // Cek ke tabel 'pengguna'
            'tipe_profil' => 'required|in:konsumen,merchant,admin',
            'alamat' => 'nullable|string',
        ];

        if ($request->tipe_profil === 'merchant') {
            $rules['nama_usaha'] = 'required|string|max:255';
            $rules['deskripsi'] = 'nullable|string';
            $rules['link_map'] = 'nullable|url';
        }

        $validatedData = $request->validate($rules);

        if ($request->tipe_profil === 'merchant') {
            $validatedData['status_verifikasi'] = 'menunggu';
        }

        $profil = Profil::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil dibuat',
            'data' => $profil
        ], 201);
    }

    public function show($id)
    {
        $profil = Profil::with(['pengguna', 'verifikator'])->find($id);

        if (!$profil) {
            return response()->json(['status' => 'error', 'message' => 'Profil tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $profil
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $profil = Profil::find($id);

        if (!$profil) {
            return response()->json(['status' => 'error', 'message' => 'Profil tidak ditemukan'], 404);
        }

        // 1. Tambahkan 'tipe_profil' ke dalam validasi
        $rules = [
            'tipe_profil' => 'sometimes|in:konsumen,merchant,admin',
            'alamat' => 'nullable|string',
        ];

        // 2. Cek apakah tipe profil berubah atau tetap
        $tipeBaru = $request->input('tipe_profil', $profil->tipe_profil);

        // 3. Aturan validasi menyesuaikan tipe profil saat ini / yang baru
        if ($tipeBaru === 'merchant') {
            $rules['nama_usaha'] = 'required_if:tipe_profil,merchant|string|max:255';
            $rules['deskripsi'] = 'nullable|string';
            $rules['link_map'] = 'nullable|url';
        }

        $validatedData = $request->validate($rules);

        // 4. LOGIKA PENTING: Jika pengguna baru saja upgrade dari konsumen ke merchant
        if ($request->has('tipe_profil') && $request->tipe_profil === 'merchant' && $profil->tipe_profil !== 'merchant') {
            $validatedData['status_verifikasi'] = 'menunggu';
            $validatedData['diverifikasi_oleh'] = null; // Reset ID admin verifikator sebelumnya (jika ada)
            $validatedData['alasan_penolakan'] = null;  // Reset alasan penolakan
        }

        $profil->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diupdate',
            'data' => $profil
        ], 200);
    }

    public function verifikasiMerchant(Request $request, $id)
    {
        $profil = Profil::find($id);

        if (!$profil || $profil->tipe_profil !== 'merchant') {
            return response()->json(['status' => 'error', 'message' => 'Data merchant tidak valid'], 404);
        }

        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'alasan_penolakan' => 'required_if:status_verifikasi,ditolak|nullable|string',
            'diverifikasi_oleh' => 'required|exists:pengguna,id' // Cek admin ke tabel 'pengguna'
        ]);

        $profil->update([
            'status_verifikasi' => $request->status_verifikasi,
            'alasan_penolakan' => $request->status_verifikasi === 'ditolak' ? $request->alasan_penolakan : null,
            'diverifikasi_oleh' => $request->diverifikasi_oleh
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status merchant berhasil diperbarui',
            'data' => $profil
        ], 200);
    }
}