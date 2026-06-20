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

        $stats = [
            'merchant_aktif' => Profil::where('tipe_profil', 'merchant')->where('status_verifikasi', 'disetujui')->count(),
            'makanan_terselamatkan' => Claim::where('status', '!=', 'batal')->sum('jumlah'),
        ];
        $totalTransaksi = Claim::count();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $users,
                'stats' => $stats
            ], 200);
        }

        return view('admin.users', compact('users', 'stats', 'totalTransaksi'));
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
public function analisisPenjualan()
{
    $totalPendapatan = Claim::where('status', 'selesai')->sum('total_harga');
    $totalMakananHemat = Claim::where('status', 'selesai')->sum('jumlah');
    $totalListingAktif = Listing::where('status', 'aktif')->count();

    // Get daily sales for the last 7 days
    $salesData = Claim::where('status', 'selesai')
        ->where('created_at', '>=', now()->subDays(7))
        ->selectRaw('DATE(created_at) as date, sum(total_harga) as total')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->pluck('total', 'date');

    $chartLabels = [];
    $chartData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $chartLabels[] = now()->subDays($i)->format('D');
        $chartData[] = $salesData->get($date, 0);
    }

    return view('admin.analisis_pen', compact('totalPendapatan', 'totalMakananHemat', 'totalListingAktif', 'chartLabels', 'chartData'));
}

public function merchantMenunggu()
{
    $merchants = Profil::with('user:id,name,email')
        ->where('tipe_profil', 'merchant')
        ->where('status_verifikasi', 'menunggu')
        ->get();

    $stats = [
        'ditunda' => $merchants->count(),
        'disetujui' => Profil::where('tipe_profil', 'merchant')->where('status_verifikasi', 'disetujui')->where('updated_at', '>=', now()->startOfWeek())->count(),
        'ditolak' => Profil::where('tipe_profil', 'merchant')->where('status_verifikasi', 'ditolak')->where('updated_at', '>=', now()->startOfWeek())->count(),
    ];

    return view('admin.merchant-menunggu', compact('merchants', 'stats'));
}

public function detailMerchant($id)
{
    $merchant = Profil::with('user')->findOrFail($id);
    return view('admin.merchant-detail', compact('merchant'));
}
}

