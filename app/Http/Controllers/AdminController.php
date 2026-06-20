<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
use App\Models\Claim;
use App\Models\Listing;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function statistik(Request $request)
    {
        $totalMerchantAktif = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'disetujui')
            ->count();
        $totalKonsumenAktif = Profil::where('tipe_profil', 'konsumen')
            ->where('status_verifikasi', 'disetujui')
            ->count();
        $totalListing = Listing::count();
        $totalKlaim = Claim::where('status', '!=', 'batal')->count();
        $totalMakananTerselamatkan = Claim::where('status', '!=', 'batal')->sum('jumlah');

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_merchant_aktif' => $totalMerchantAktif,
                    'total_konsumen_aktif' => $totalKonsumenAktif,
                    'total_listing' => $totalListing,
                    'total_klaim' => $totalKlaim,
                    'total_makanan_terselamatkan' => (int) $totalMakananTerselamatkan,
                ],
            ], 200);
        }

        return view('dashboard-admin', compact(
            'totalMerchantAktif',
            'totalKonsumenAktif',
            'totalListing',
            'totalKlaim',
            'totalMakananTerselamatkan'
        ));
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

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $users,
            ], 200);
        }

        return view('admin.users', ['users' => $users]);
    }

    public function ubahStatusUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === $request->user()->id) {
            $pesanError = 'Tidak dapat mengubah status akun sendiri.';

            return $request->wantsJson()
                ? response()->json(['status' => 'error', 'message' => $pesanError], 400)
                : redirect()->back()->withErrors(['error' => $pesanError]);
        }

        $request->validate([
            'status' => 'required|in:aktif,nonaktif,diblokir',
        ]);

        $user->update(['status' => $request->status]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status user berhasil diperbarui.',
                'data' => $user,
            ], 200);
        }

        return redirect()->back()->with('success', 'Status user berhasil diperbarui.');
    }

    public function moderasiListing(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);

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

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status listing berhasil diperbarui.',
                'data' => $listing,
            ], 200);
        }

        return redirect()->back()->with('success', 'Status listing berhasil diperbarui.');
    }

    public function merchantMenunggu(Request $request)
    {
        $merchant = Profil::with('user:id,name,email,no_telphone')
            ->where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $merchant,
            ], 200);
        }

        return view('admin.merchant-menunggu', ['merchants' => $merchant]);
    }
}
