<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Listing;
use App\Models\Claim;

// Menjalankan tugas setiap menit
Schedule::call(function () {
    
    // 1. Otomatisasi mengubah status makanan menjadi 'expired'
    // Jika batas_waktu sudah lewat dari sekarang dan status masih 'aktif'
    Listing::where('batas_waktu', '<', now())
           ->where('status', 'aktif')
           ->update(['status' => 'expired']);

    // 2. Pembatalan klaim otomatis yang tidak dibayar dalam 15 menit
    $expiredClaims = Claim::where('status_pembayaran', 'belum_dibayar')
           ->where('created_at', '<', now()->subMinutes(15))
           ->where('status', 'pending')
           ->get();

    foreach($expiredClaims as $claim) {
        // Ubah status klaim
        $claim->update([
            'status' => 'batal', 
            'status_pembayaran' => 'gagal'
        ]);

        // Kembalikan stok ke listing terkait
        $claim->listing()->increment('stok_sisa', $claim->jumlah);
    }

})->everyMinute();