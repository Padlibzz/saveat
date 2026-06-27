<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
use App\Models\Claim;
use App\Models\Listing;
use App\Models\Profil;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalPengguna = User::count();
        $aktifHariIni = User::where('status', 'aktif')->count();
        $baruDaftar = User::whereDate('created_at', Carbon::today())->count();
        $makananTerselamatkan = Claim::where('status', '!=', 'batal')->sum('jumlah');
        $totalDilaporkan = AbuseReport::count();

        return view('admin.dashboard', compact(
            'totalPengguna',
            'aktifHariIni',
            'baruDaftar',
            'makananTerselamatkan',
            'totalDilaporkan'
        ));
    }

    public function statistik(Request $request)
    {
        $totalPengguna = User::count();
        $aktifHariIni = User::where('status', 'aktif')->count();
        $baruDaftar = User::whereDate('created_at', Carbon::today())->count();
        $makananTerselamatkan = Claim::where('status', '!=', 'batal')->sum('jumlah');
        $totalDilaporkan = AbuseReport::count();

        $totalMerchantAktif = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'disetujui')
            ->count();
        $totalKonsumenAktif = Profil::where('tipe_profil', 'konsumen')
            ->where('status_verifikasi', 'disetujui')
            ->count();
        $totalListing = Listing::count();
        $totalKlaim = Claim::where('status', '!=', 'batal')->count();
        $totalMakananTerselamatkan = $makananTerselamatkan; // Using the same value as $makananTerselamatkan

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

        return view('admin.dashboard', compact(
            'totalPengguna',
            'aktifHariIni',
            'baruDaftar',
            'makananTerselamatkan',
            'totalDilaporkan',
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
                'stats' => $stats,
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
            $blur = AbuseReport::where('listing_id', $id)
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

    public function analisisPenjualan(Request $request)
    {
        $filter = $request->input('filter', '7hari');

        $query = Claim::where('status', 'selesai');

        if ($filter === 'bulanini') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($filter === 'tahunini') {
            $query->whereYear('created_at', now()->year);
        } else {
            $query->where('created_at', '>=', now()->subDays(7));
        }

        $totalPendapatan = (clone $query)->sum('total_harga');
        $totalMakananHemat = (clone $query)->sum('jumlah');
        $totalListingAktif = Listing::where('status', 'aktif')->count();

        // Data grafik disesuaikan berdasarkan filter (default 7 hari)
        $salesData = (clone $query)
            ->selectRaw('DATE(created_at) as date, sum(total_harga) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartData = [];

        if ($filter === 'bulanini') {
            $daysInMonth = now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = now()->format('Y-m-').str_pad($i, 2, '0', STR_PAD_LEFT);
                $chartLabels[] = $i;
                $chartData[] = $salesData->get($date, 0);
            }
        } elseif ($filter === 'tahunini') {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = $months[$i - 1];
                $monthSales = Claim::where('status', 'selesai')
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', $i)
                    ->sum('total_harga');
                $chartData[] = $monthSales;
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $chartLabels[] = now()->subDays($i)->format('D');
                $chartData[] = $salesData->get($date, 0);
            }
        }

        return view('admin.analisis-penjualan', compact('totalPendapatan', 'totalMakananHemat', 'totalListingAktif', 'chartLabels', 'chartData', 'filter'));
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

    public function merchantDetail($id)
    {
        $merchant = Profil::with('user')->findOrFail($id);

        return view('admin.merchant-detail', compact('merchant'));
    }

    public function detailMerchant($id)
    {
        $merchant = Profil::with('user')->findOrFail($id);

        return view('admin.merchant-detail', compact('merchant'));
    }

    public function halamanVerifikasi(Request $request)
    {
        // 1. Data Statistik Riil dari Database
        $totalDitunda = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'menunggu')
            ->count();

        $totalDisetujuiMingguIni = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'disetujui')
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $totalDitolakMingguIni = Profil::where('tipe_profil', 'merchant')
            ->where('status_verifikasi', 'ditolak')
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        // 2. Query List Merchant + Fitur Pencarian (Search)
        $query = Profil::with('user')->where('tipe_profil', 'merchant');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Menggunakan kolom database 'nama_usaha' yang sesuai schema
                $q->where('nama_usaha', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $merchants = $query->orderByRaw("FIELD(status_verifikasi, 'menunggu', 'disetujui', 'ditolak') ASC")
            ->orderBy('created_at', 'desc')
            ->paginate(6); // 6 item per halaman sangat pas untuk grid desktop

        return view('admin.verifikasi-merchant', compact(
            'totalDitunda',
            'totalDisetujuiMingguIni',
            'totalDitolakMingguIni',
            'merchants'
        ));
    }

    public function setujuiMerchant(Request $request, $id)
    {
        $profil = Profil::find($id);
        if (! $profil) {
            return $request->wantsJson() ? response()->json(['status' => 'error', 'message' => 'Tidak ditemukan'], 404) : back()->with('error', 'Data tidak ditemukan.');
        }

        $profil->update(['status_verifikasi' => 'disetujui']);

        if ($profil->user_id) {
            User::where('id', $profil->user_id)->update(['peran' => 'merchant']);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Pengajuan disetujui!'], 200);
        }

        return back()->with('success', 'Merchant berhasil disetujui.');
    }

    public function tolakMerchant(Request $request, $id)
    {
        $profil = Profil::find($id);
        if (! $profil) {
            return $request->wantsJson() ? response()->json(['status' => 'error', 'message' => 'Tidak ditemukan'], 404) : back()->with('error', 'Data tidak ditemukan.');
        }

        $profil->update(['status_verifikasi' => 'ditolak']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Pengajuan ditolak.'], 200);
        }

        return back()->with('error', 'Pengajuan merchant ditolak.');
    }
}
