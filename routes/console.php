<?php

use App\Models\Claim;
use App\Models\Listing;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    // Auto expired listing -> 'tutup' + kirim notifikasi ke merchant
    $listingExpired = Listing::with('merchant')
        ->where('batas_waktu', '<', now())
        ->whereIn('status', ['aktif', 'hampir_habis'])
        ->get();

    foreach ($listingExpired as $listing) {
        $listing->update(['status' => 'tutup']);

        if ($listing->merchant && $listing->merchant->user_id) {
            NotificationService::listingExpired($listing->merchant->user_id, $listing->nama);
        }
    }

    // Status dinamis "hampir habis": stok sisa < 20% dari stok total
    Listing::where('status', 'aktif')
        ->where('batas_waktu', '>=', now())
        ->whereRaw('stok_sisa > 0 AND stok_sisa < (stok_total * 0.2)')
        ->update(['status' => 'hampir_habis']);

    // Listing yang stok nol -> 'tutup'
    Listing::whereIn('status', ['aktif', 'hampir_habis'])
        ->where('stok_sisa', '<=', 0)
        ->update(['status' => 'tutup']);

    // Auto-cancel klaim tidak dibayar 15 menit
    $expiredClaims = Claim::where('status_pembayaran', 'belum_dibayar')
        ->where('created_at', '<', now()->subMinutes(15))
        ->where('status', 'pending')
        ->get();

    foreach($expiredClaims as $claim) {
        $claim->update([
            'status' => 'batal', 
            'status_pembayaran' => 'gagal'
        ]);

        $listing = $claim->listing;
        if ($listing) {
            $listing->increment('stok_sisa', $claim->jumlah);

            if ($listing->status === 'tutup' && $listing->batas_waktu > now()) {
                $persenSisa = $listing->stok_sisa / max($listing->stok_total, 1);
                $listing->update(['status' => $persenSisa < 0.2 ? 'hampir_habis' : 'aktif']);
            }
        }
    }

})->everyMinute();