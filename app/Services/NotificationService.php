<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function klaimBerhasil(int $userId, int $claimId, string $namaListing, string $batasWaktu): void
    {
        Notification::create([
            'user_id'   => $userId,
            'claim_id'  => $claimId,
            'jenis'     => 'claims_berhasil',
            'judul'     => 'Klaim Berhasil!',
            'pesan'     => "Klaimmu untuk \"{$namaListing}\" berhasil. Ambil sebelum {$batasWaktu}.",
            'is_read'   => false,
        ]);
    }

    public static function klaimMasuk(int $merchantUserId, int $claimId, string $namaListing): void
    {
        Notification::create([
            'user_id'   => $merchantUserId,
            'claim_id'  => $claimId,
            'jenis'     => 'claims_masuk',
            'judul'     => 'Ada Klaim Masuk!',
            'pesan'     => "Ada yang klaim makananmu — \"{$namaListing}\".",
            'is_read'   => false,
        ]);
    }

    public static function pesananSelesai(int $userId, int $claimId, string $namaListing): void
    {
        Notification::create([
            'user_id'  => $userId,
            'claim_id' => $claimId,
            'jenis'    => 'pesanan_selesai',
            'judul'    => 'Pesanan Selesai',
            'pesan'    => "Pesanan \"{$namaListing}\" berhasil diambil. Terima kasih sudah menyelamatkan makanan!",
            'is_read'  => false,
        ]);
    }

    public static function listingExpired(int $merchantUserId, string $namaListing): void
    {
        Notification::create([
            'user_id'  => $merchantUserId,
            'claim_id' => null,
            'jenis'    => 'listing_expired',
            'judul'    => 'Listing Ditutup Otomatis',
            'pesan'    => "Listing \"{$namaListing}\" telah ditutup otomatis karena waktu pengambilan sudah habis.",
            'is_read'  => false,
        ]);
    }

    public static function menungguPembayaran(int $userId, int $claimId, string $namaListing): void
    {
        Notification::create([
            'user_id'   => $userId,
            'claim_id'  => $claimId,
            'jenis'     => 'menunggu_pembayaran',
            'judul'     => 'Menunggu Pembayaran',
            'pesan'     => "Pesanan \"{$namaListing}\" berhasil dibuat. Yuk, selesaikan pembayaranmu sekarang!",
            'is_read'   => false,
        ]);
    }
}