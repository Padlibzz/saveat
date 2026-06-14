<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Listing;
use App\Models\Claim;

Schedule::call(function () {
    // Auto expired: listing yang sudah lewat batas waktu
    Listing::where('batas_waktu', '<', now())
        ->where('status', ['aktif', 'hampir_habis'])
        ->update(['status' => 'tutup']);

    // Status dinamis "hampir habis": stok sisa < 20% dari stok total
    Listing::where('status', 'aktif')
        ->where('batas_waktu', '>=', now())
        ->whereRaw('stok_sisa > 0 AND stok_sisa < (stok_total * 0.2)')
        ->update(['status' => 'hampir_habis']);

    // Listing yang stok sisa = 0 -> 'tutup'
    Listing::whereIn('status', ['aktif', 'hampir_habis'])
        ->where('stok_sisa', '<=', 0)
        ->update(['status' => 'tutup']);

    // Pembatalan klaim otomatis yang tidak dibayar dalam 15 menit
    $expiredClaims = Claim::where('status_pembayaran', 'belum_dibayar')
        ->where('created_at', '<', now()->subMinutes(15))
        ->where('status', 'pending')
        ->get();

    foreach($expiredClaims as $claim) {
        $claim->update([
            'status' => 'batal', 
            'status_pembayaran' => 'gagal'
        ]);

        // Kembalikan stok ke listing terkait dan jika listing sebelumnya 'tutup' karena stok 0, balikkan ke 'aktif'/'hampir_habis'
        $listing = $claim->listing;
        $listing->increment('stok_sisa', $claim->jumlah);

        if ($listing->status === 'tutup' && $listing->batas_waktu > now()) {
            $persenSisa = $listing->stok_sisa / max($listing->stok_total, 1);
            $listing->update(['status' => $persenSisa < 0.2 ? 'hampir_habis' : 'aktif']);
        }
    }

})->everyMinute();