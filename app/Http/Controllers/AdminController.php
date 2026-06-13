<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Profil;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function statistik()
    {
        $totalMerchantAktif = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'disetujui')
            ->count();

        $totalListing = Listing::count();
        $totalKlaim = Claim::where('status', '!=', 'batal')->count();
        $totalMakananTerselamatkan = Claim::where('status', '!=', 'batal')->sum('jumlah');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_merchant_aktif' => $totalMerchantAktif,
                'total_listing' => $totalListing,
                'total_klaim' => $totalKlaim,
                'total_makanan_terselamatkan' => (int) $totalMakananTerselamatkan,
            ],
        ], 200);
    }

    public function daftarUser(Request $request)
    {
        $query = User::query();

        if($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ], 200);
    }

    public function ubahStatusUser(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        if ($user->id === $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat mengubah status akun sendiri.',
            ], 400);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'status' => 'required|in:aktif,nonaktif,diblokir',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status user berhasil diperbarui.',
            'data' => $user,
        ], 200);
    }
}
