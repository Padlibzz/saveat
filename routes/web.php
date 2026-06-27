<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\MerchantListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RecommendationController;
use App\Models\Claim;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// ================= HALAMAN PUBLIK =================
Route::get('/', [LandingController::class, 'index']);
Route::get('/listing-makanan', [ListingController::class, 'index'])->name('listing-makanan');

// ================= GUEST MIDDLEWARE (Belum Login) =================
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/auth/register', [AuthController::class, 'register']);

    // Password reset routes
    Route::get('/auth/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/auth/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/auth/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
});

// Google Auth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// ================= AUTH MIDDLEWARE (Sudah Login) =================
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->peran) {
        'admin' => redirect()->route('admin.dashboard'),
        'merchant' => redirect()->route('merchant.dashboard'),
        default => redirect()->route('dashboard.konsumen'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'readWeb'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'readAllWeb'])->name('notifikasi.read-all');
    Route::get('/api/notifikasi/unread-count', [NotificationController::class, 'unreadCount'])->name('notifikasi.unread-count');

    Route::get('/pengaturan/keamanan', [SecurityController::class, 'index'])->name('keamanan.index');
    Route::put('/pengaturan/keamanan/password', [SecurityController::class, 'updatePassword'])->name('keamanan.password.update');
    
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    
    // API internal untuk di-fetch oleh JavaScript di Blade
    Route::get('/chat/messages/{receiverId}', [ChatController::class, 'getMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    
    // ================= AREA KONSUMEN =================
    Route::middleware(['role:konsumen'])->group(function () {
        Route::get('/dashboard-konsumen', [RecommendationController::class, 'dashboard'])->name('dashboard.konsumen');
        Route::get('/notifikasi', [NotificationController::class, 'konsumenIndex'])->name('customer.notifikasi');
    });

    Route::get('/api/recommendations', [RecommendationController::class, 'index']);
    Route::get('/claim/success/{id}', [ListingController::class, 'claimSuccess'])->name('claim.success');

    // API Endpoints for Frontend Integration
    Route::post('/api/claim/store', function (Request $request) {
        $request->validate([
            'listing_id'   => 'required|exists:listings,id',
            'jumlah'       => 'required|integer|min:1',
            'total_harga'  => 'required|numeric|min:0',
            'pickup_date'  => 'required|date',
            'pickup_time'  => 'required',
        ]);

        $exists = Claim::where('listing_id', $request->listing_id)
            ->where('pickup_date', $request->pickup_date)
            ->where('pickup_time', $request->pickup_time)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Waktu pengambilan tersebut sudah dipilih pelanggan lain. Silakan pilih jam yang lain.'
            ], 422);
        }

        $claim = Claim::create([
            'user_id' => auth()->id(),
            'listing_id' => $request->listing_id,
            'jumlah' => $request->jumlah,
            'total_harga' => $request->total_harga,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'status' => 'pending',
            'status_pembayaran' => 'belum_dibayar',
            'kode_klaim' => 'SVAT-'.strtoupper(Str::random(6)),
        ]);

        return response()->json([
            'status' => 'success',
            'claim_id' => $claim->id,
        ]);
    });

    Route::post('/api/payment/create/{claimId}', [PaymentController::class, 'createTransaction']);

    // Pendaftaran Merchant
    Route::get('/merchant-application', function () {
        return view('merchant-application');
    })->name('merchant.application');
    Route::post('/merchant-application', [ProfilController::class, 'applyMerchant'])->name('merchant.application.submit');

    // ================= AREA MERCHANT =================
    Route::middleware(['role:merchant'])->prefix('merchant')->group(function () {
        Route::get('/dashboard', [MerchantDashboardController::class, 'index'])->name('merchant.dashboard');
        Route::get('/upload-makanan', [MerchantListingController::class, 'create'])->name('merchant.upload');
        Route::post('/upload-makanan', [MerchantListingController::class, 'storeWeb'])->name('merchant.upload.submit');
        Route::get('/produk-aktif', [MerchantListingController::class, 'indexWeb'])->name('merchant.produk-aktif');
        Route::get('/produk-aktif/{id}/edit', [MerchantListingController::class, 'edit'])->name('merchant.listing.edit');
        Route::put('/produk-aktif/{id}', [MerchantListingController::class, 'updateWeb'])->name('merchant.listing.update');
        Route::delete('/produk-aktif/{id}', [MerchantListingController::class, 'destroy'])->name('merchant.listing.destroy');
        Route::post('/listing/{id}/tutup', [MerchantListingController::class, 'tutup'])->name('merchant.listing.tutup');

        Route::get('/notifikasi', [NotificationController::class, 'merchantIndex'])->name('merchant.notifikasi');
        
        Route::get('/claim-masuk', [MerchantDashboardController::class, 'klaimMasukWeb'])->name('merchant.klaim-masuk');
        Route::post('/claim/{id}/verifikasi', function (Request $request, $id) {
            $claim = Claim::findOrFail($id);
            $claim->update(['status' => 'diambil']);

            return redirect()->back()->with('success', 'Pesanan atas nama pelanggan berhasil diselesaikan!');
        })->name('merchant.claim.verifikasi');

        Route::get('/scan-qr', [ClaimController::class, 'scanForm'])->name('merchant.scan-qr');
        Route::post('/scan-qr', [ClaimController::class, 'verifikasiWeb'])->name('merchant.scan-qr.submit');
    });

    // ================= AREA ADMIN =================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'statistik'])->name('admin.dashboard');
        Route::get('/profile', [ProfilController::class, 'index'])->name('admin.profile');
        Route::get('/analisis-penjualan', [AdminController::class, 'analisisPenjualan'])->name('admin.analisis');
        Route::get('/users', [AdminController::class, 'daftarUser'])->name('admin.users');
        Route::get('/merchants/menunggu', [AdminController::class, 'merchantMenunggu'])->name('admin.merchant-menunggu');
        Route::get('/merchants/{id}', [AdminController::class, 'merchantDetail'])->name('admin.merchant-detail');
        Route::patch('/merchants/{id}/verifikasi', [ProfilController::class, 'verifikasiMerchant'])->name('admin.merchant-verifikasi');
        Route::get('/verifikasi-merchant', [AdminController::class, 'halamanVerifikasi'])->name('admin.verifikasi.index');
        Route::post('/merchant/{id}/setujui', [AdminController::class, 'setujuiMerchant'])->name('admin.merchant.setujui');
        Route::post('/merchant/{id}/tolak', [AdminController::class, 'tolakMerchant'])->name('admin.merchant.tolak');
    });

    // ================= TRANSACTION ROUTES =================
    Route::post('/proses-transaksi', [ClaimController::class, 'prosesTransaksi'])->name('transaksi.proses');
    Route::post('/konfirmasi-pembayaran/{id}', [ClaimController::class, 'konfirmasiPembayaran'])->name('transaksi.konfirmasi');
    Route::get('/pesanan', [ClaimController::class, 'pesananAktif'])->name('pesanan.aktif');

    Route::get('/pesanan/{id}', function ($id) {
        $claim = Claim::with('listing.merchant')->findOrFail($id);

        return view('customer.pesanan-detail', compact('claim'));
    })->name('pesanan.detail');

    // --- TAMBAHAN BARU: RUTE UNTUK HALAMAN QR CODE CUSTOMER ---
    Route::get('/pesanan/qr/{id}', function ($id) {
        $claim = Claim::with('listing.merchant')->findOrFail($id);

        // Mengarahkan ke file claim-success.blade.php sesuai permintaan Anda
        return view('customer.claim-success', compact('claim'));
    })->name('pesanan.qr');

    Route::get('/riwayat-pesanan', [ClaimController::class, 'riwayat'])->name('riwayat.pesanan');

    // Shared Routes
    Route::get('/profile', [ProfilController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfilController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile/update', [ProfilController::class, 'updateProfile'])
        ->name('profile.update');

    Route::get('/checkout/{id}', [ListingController::class, 'checkout'])->name('checkout');
});
