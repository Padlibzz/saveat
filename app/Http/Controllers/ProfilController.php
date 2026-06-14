<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $query = Profil::with('user');

        if ($request->has('tipe')) {
            $query->where('tipe_profil', $request->tipe);
        }

        $profils = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $profils,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
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
            'data' => $profil,
        ], 201);
    }

    public function show($id)
    {
        $profil = Profil::with(['user', 'verifikator'])->find($id);

        if (! $profil) {
            return response()->json(['status' => 'error', 'message' => 'Profil tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $profil,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $profil = Profil::find($id);

        if (! $profil) {
            return response()->json(['status' => 'error', 'message' => 'Profil tidak ditemukan'], 404);
        }

        if ($profil->user_id !== $request->user()->id && $request->user()->peran !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Aksi ditolak. Anda tidak memiliki akses untuk mengubah profil ini.',
            ], 403);
        }

        // Validasi input gabungan antara tabel users dan tabel profils
        $rules = [
            'name' => 'sometimes|string|max:255',
            'no_telphone' => 'sometimes|string',
            'profil_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tipe_profil' => 'sometimes|in:konsumen,merchant,admin',
            'alamat' => 'nullable|string',
        ];

        $tipeBaru = $request->input('tipe_profil', $profil->tipe_profil);

        if ($tipeBaru === 'merchant') {
            $rules['nama_usaha'] = 'required_if:tipe_profil,merchant|string|max:255';
            $rules['deskripsi'] = 'nullable|string';
            $rules['link_map'] = 'nullable|url';
        }

        $validatedData = $request->validate($rules);

        // 1. Proses Sinkronisasi dan Pembaruan Data pada Tabel Users
        $user = $profil->user;
        if ($user) {
            if ($request->filled('name')) {
                $user->name = $request->name;
            }
            if ($request->filled('no_telphone')) {
                $user->no_telphone = $request->no_telphone;
            }

            // Logika File Upload untuk Foto Profil Pengguna
            if ($request->hasFile('profil_image')) {
                // Hapus file foto lama dari storage fisik jika ada untuk menghemat ruang
                if ($user->profil_image && Storage::disk('public')->exists($user->profil_image)) {
                    Storage::disk('public')->delete($user->profil_image);
                }

                $file = $request->file('profil_image');
                $namaFoto = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('profiles', $namaFoto, 'public');
                $user->profil_image = $path;
            }
            $user->save();
        }

        // 2. Proses Pembaruan Data pada Tabel Profils
        $profilFields = ['tipe_profil', 'alamat', 'nama_usaha', 'deskripsi', 'link_map'];
        $inputProfil = array_intersect_key($validatedData, array_flip($profilFields));

        if ($request->has('tipe_profil') && $request->tipe_profil === 'merchant' && $profil->tipe_profil !== 'merchant') {
            $inputProfil['status_verifikasi'] = 'menunggu';
            $inputProfil['diverifikasi_oleh'] = null;
            $inputProfil['alasan_penolakan'] = null;
        }

        $profil->update($inputProfil);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil dan data user berhasil diperbarui.',
            'data' => $profil->load('user'),
        ], 200);
    }

    public function verifikasiMerchant(Request $request, $id)
    {
        if ($request->user()->peran !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses admin'], 403);
        }

        $profil = Profil::find($id);

        if (! $profil || $profil->tipe_profil !== 'merchant') {
            return response()->json(['status' => 'error', 'message' => 'Data merchant tidak valid'], 404);
        }

        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'alasan_penolakan' => 'required_if:status_verifikasi,ditolak|nullable|string',
            'diverifikasi_oleh' => 'required|exists:users,id',
        ]);

        $profil->update([
            'status_verifikasi' => $request->status_verifikasi,
            'alasan_penolakan' => $request->status_verifikasi === 'ditolak' ? $request->alasan_penolakan : null,
            'diverifikasi_oleh' => $request->diverifikasi_oleh,
        ]);

        if ($request->status_verifikasi === 'disetujui') {
            $profil->user->update(['peran' => 'merchant']);
        } elseif ($request->status_verifikasi === 'ditolak') {
            $profil->user->update(['peran' => 'konsumen']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Status merchant berhasil diperbarui dan peran disinkronkan',
            'data' => $profil->load('user'),
        ], 200);
    }
}