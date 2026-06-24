<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Listing;
use Illuminate\Http\Request;

class MerchantDashboardController extends Controller
{
    /**
     * ====================================================================
     * FUNGSI BAWAAN BACKEND: STATISTIK TOKO (API JSON)
     * ====================================================================
     */
    public function statistik(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil merchant tidak ditemukan.',
            ], 404);
        }

        $listingIds = Listing::where('merchant_id', $merchant->id)->pluck('id');

        $claimsValid = Claim::whereIn('listing_id', $listingIds)
            ->where('status', '!=', 'batal');

        $totalPorsiTerjual = (clone $claimsValid)->sum('jumlah');
        $totalPendapatan = (clone $claimsValid)
            ->where('status_pembayaran', 'sudah_dibayar')
            ->sum('total_harga');

        $totalPembeliUnik = (clone $claimsValid)->distinct('user_id')->count('user_id');

        $makananTerselamatkan = $totalPorsiTerjual;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_porsi_terjual' => (int) $totalPorsiTerjual,
                'total_pendapatan' => (float) $totalPendapatan,
                'total_pembeli_unik' => $totalPembeliUnik,
                'makanan_terselamatkan' => (int) $makananTerselamatkan,
            ],
        ], 200);
    }

    /**
     * ====================================================================
     * FUNGSI BAWAAN BACKEND: KLAIM/PESANAN MASUK (API JSON)
     * ====================================================================
     */
    public function klaimMasuk(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil merchant tidak ditemukan.',
            ], 404);
        }

        $listingIds = Listing::where('merchant_id', $merchant->id)->pluck('id');

        $query = Claim::with(['user:id,name,username', 'listing:id,nama'])
            ->whereIn('listing_id', $listingIds)
            ->orderBy('created_at', 'desc');

        if ($request->filled('listing_id')) {
            $query->where('listing_id', $request->listing_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $klaims = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $klaims,
        ], 200);
    }
    public function index(Request $request)
    {
        if ($request->user()->peran !== 'merchant' || ! $request->user()->merchant) {
            return redirect()->route('dashboard')->with('error', 'Anda belum terdaftar sebagai merchant resmi.');
        }

        $merchant = $request->user()->merchant;

        $listingIds = Listing::where('merchant_id', $merchant->id)->pluck('id');

        $claimsValid = Claim::whereIn('listing_id', $listingIds)->where('status', '!=', 'batal');

        // Penghasilan total 
        $totalPendapatan = (clone $claimsValid)->where('status_pembayaran', 'sudah_dibayar')->sum('total_harga');

        // Makanan terjual HARI INI
        $makananTerjualHariIni = (clone $claimsValid)
            ->whereDate('created_at', today())
            ->sum('jumlah');

        $totalPorsiTerjual = $makananTerjualHariIni;

        // Total pembeli unik (all-time) + pembeli baru minggu ini
        $totalPembeliUnik = (clone $claimsValid)->distinct('user_id')->count('user_id');
        $pembeliBaruMingguIni = (clone $claimsValid)
            ->where('created_at', '>=', now()->subDays(7))
            ->distinct('user_id')
            ->count('user_id');

        // Grafik "Kurs Penjualan" — breakdown 7 hari terakhir
        $grafikPenjualan = $this->grafikPenjualan7Hari($listingIds);

        // Aktivitas Terkini — gabungan klaim masuk, ulasan baru, listing segera berakhir
        $aktivitasTerkini = $this->aktivitasTerkini($merchant, $listingIds);

        return view('dashboard-merchant', compact(
            'totalPendapatan',
            'makananTerjualHariIni',
            'totalPorsiTerjual',
            'totalPembeliUnik',
            'pembeliBaruMingguIni',
            'grafikPenjualan',
            'aktivitasTerkini'
        ));
    }

    private function grafikPenjualan7Hari($listingIds): array
    {
        $hariLabel = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $hasil = [];

        $mulai = now()->subDays(6)->startOfDay();

        for ($i = 0; $i < 7; $i++) {
            $tanggal = $mulai->copy()->addDays($i);

            $totalPorsi = Claim::whereIn('listing_id', $listingIds)
                ->where('status', '!=', 'batal')
                ->whereDate('created_at', $tanggal)
                ->sum('jumlah');

            $hasil[] = [
                'label' => $hariLabel[$tanggal->dayOfWeekIso - 1], // 1=Senin ... 7=Minggu
                'tanggal' => $tanggal->format('Y-m-d'),
                'total' => (int) $totalPorsi,
            ];
        }

        return $hasil;
    }

    private function aktivitasTerkini($merchant, $listingIds): array
    {
        $aktivitas = collect();

        $klaimTerbaru = Claim::with(['user:id,name', 'listing:id,nama'])
            ->whereIn('listing_id', $listingIds)
            ->where('status', '!=', 'batal')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($klaimTerbaru as $klaim) {
            $aktivitas->push([
                'tipe' => 'klaim',
                'icon' => 'bag-shopping',
                'judul' => $klaim->jumlah.'x '.($klaim->listing->nama ?? 'Makanan').' diklaim',
                'keterangan' => 'Oleh '.($klaim->user->name ?? 'Konsumen'),
                'waktu' => $klaim->created_at,
            ]);
        }

        $ulasanTerbaru = \App\Models\Review::whereIn('listing_id', $listingIds)
            ->where('rating', 5)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($ulasanTerbaru as $ulasan) {
            $aktivitas->push([
                'tipe' => 'ulasan',
                'icon' => 'star',
                'judul' => 'Ulasan Bintang 5 Baru',
                'keterangan' => $ulasan->komentar ? '"'.$ulasan->komentar.'"' : 'Tanpa komentar',
                'waktu' => $ulasan->created_at,
            ]);
        }

        $listingSegeraBerakhir = Listing::whereIn('id', $listingIds)
            ->whereIn('status', ['aktif', 'hampir_habis'])
            ->whereBetween('batas_waktu', [now(), now()->addHour()])
            ->orderBy('batas_waktu', 'asc')
            ->take(5)
            ->get();

        foreach ($listingSegeraBerakhir as $listing) {
            $aktivitas->push([
                'tipe' => 'segera_berakhir',
                'icon' => 'clock',
                'judul' => 'Listing Segera Berakhir',
                'keterangan' => $listing->nama.' • Sisa '.now()->diffInMinutes($listing->batas_waktu).' menit',
                'waktu' => now(),
            ]);
        }

        return $aktivitas->sortByDesc('waktu')->take(6)->values()->all();
    }

    public function klaimMasukWeb(Request $request)
    {
        // Karena tidak ada view khusus 'claim-masuk' di branch frontend,
        // kita arahkan ke dashboard merchant sebagai fallback atau bisa diimplementasi ulang
        // menggunakan data yang sudah ada di dashboard.
        return redirect()->route('merchant.dashboard')->with('info', 'Halaman klaim masuk belum tersedia di desain baru.');
    }
}
