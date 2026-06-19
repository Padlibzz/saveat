<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
use App\Models\Claim;
use App\Models\Listing;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        if ($request->filled('peran')) {
            $query->where('peran', $request->peran);
        }

        if ($request->filled('status')) {
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

        $validator = Validator::make($request->all(), [
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

    public function moderasiListing(Request $request, $id)
    {
        $listing = Listing::find($id);

        if (! $listing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'aksi' => 'required|in:arsipkan,aktifkan',
            'alasan' => 'required_if:aksi,arsipkan|nullable|string|max:500',
        ]);

        $statusBaru = $request->aksi === 'arsipkan' ? 'diarsipkan' : 'aktif';
        $listing->update(['status' => $statusBaru]);

        if ($request->aksi === 'arsipkan') {
            AbuseReport::where('listing_id', $id)
                ->where('status', 'menunggu')
                ->update(['status' => 'selesai']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Status listing berhasil diperbarui.',
            'data' => $listing,
        ], 200);
    }

    public function merchantMenunggu()
    {
        $merchant = Profil::with('user:id,name,email,no_telphone')
            ->where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $merchant,
        ], 200);
    }
}
