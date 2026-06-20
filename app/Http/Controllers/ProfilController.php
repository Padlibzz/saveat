<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profil = Profil::where('user_id', $user->id)->first();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $profil ? $profil->load('user') : null,
            ], 200);
        }

        return view('profile', compact('profil'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tipe_profil' => 'required|in:konsumen,merchant,admin',
            'alamat' => 'nullable|string',
        ];

        if ($request->tipe_profil === 'merchant') {
            $rules['nama_usaha'] = 'required|string|max:255';
            $rules['deskripsi'] = 'nullable|string';
            $rules['link_map'] = 'nullable|url';
        }

        $validatedData = $request->validate($rules);

        $validatedData['user_id'] = $request->user()->id;

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
            // Koordinat lokasi toko — dipakai untuk hitung jarak ke konsumen (B02)
            $rules['latitude'] = 'nullable|numeric|between:-90,90';
            $rules['longitude'] = 'nullable|numeric|between:-180,180';
        }

        $validatedData = $request->validate($rules);

        $user = $profil->user;
        if ($user) {
            if ($request->filled('name')) {
                $user->name = $request->name;
            }
            if ($request->filled('no_telphone')) {
                $user->no_telphone = $request->no_telphone;
            }

            if ($request->hasFile('profil_image')) {
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
        $profilFields = ['tipe_profil', 'alamat', 'nama_usaha', 'deskripsi', 'link_map', 'latitude', 'longitude'];
        $inputProfil = array_intersect_key($validatedData, array_flip($profilFields));

        if ($request->has('tipe_profil') && $request->tipe_profil === 'merchant' && $profil->tipe_profil !== 'merchant') {
            $inputProfil['status_verifikasi'] = 'menunggu';
            $inputProfil['diverifikasi_oleh'] = null;
            $inputProfil['alasan_penolakan'] = null;
        }

        $profil->update($inputProfil);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profil dan data user berhasil diperbarui.',
                'data' => $profil->load('user'),
            ], 200);
        }

        return redirect()->route('profile')->with('success', 'Profil dan data user berhasil diperbarui.');
    }

    public function applyMerchant(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $user = Auth::user();
        if (!$user) {
            return back()->with('error', 'User belum login.');
        }

        $profil = $user->profil;

        if (!$profil) {
            return back()->with('error', 'Profil tidak ditemukan.');
        }

        $request->merge([
            'tipe_profil' => 'merchant'
        ]);

        return $this->update($request, $profil->id);
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

    /**
     * Update lokasi konsumen (dipanggil saat toggle "Layanan Lokasi" diaktifkan,
     * browser mengirim koordinat GPS user). Khusus untuk profil milik sendiri.
     */
    public function updateLokasi(Request $request)
    {
        $request->validate([
            'latitude' => 'required_if:izin_lokasi,true|nullable|numeric|between:-90,90',
            'longitude' => 'required_if:izin_lokasi,true|nullable|numeric|between:-180,180',
            'izin_lokasi' => 'required|boolean',
        ]);

        $profil = $request->user()->profil;

        if (! $profil) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Profil tidak ditemukan.'], 404);
            }
            return back()->with('error', 'Profil tidak ditemukan.');
        }

        $profil->update([
            'latitude' => $request->izin_lokasi ? $request->latitude : null,
            'longitude' => $request->izin_lokasi ? $request->longitude : null,
            'izin_lokasi' => $request->boolean('izin_lokasi'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $request->izin_lokasi
                    ? 'Lokasi berhasil diaktifkan.'
                    : 'Layanan lokasi dinonaktifkan.',
                'data' => $profil,
            ], 200);
        }

        return redirect()->route('profile')->with('success', $request->izin_lokasi
            ? 'Lokasi berhasil diaktifkan.'
            : 'Layanan lokasi dinonaktifkan.');
    }
}
