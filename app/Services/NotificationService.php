<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function klaimBerhasil(int $userId, int $claimId, string $namaListing, string $batasWaktu): void
    {
        Notification::create([
            'user_id' => $userId,
            'claim_id' => $claimId,
            'jenis' => 'claims_berhasil',
            'judul' => 'Pesanan Diterima',
            'pesan' => "Pesanan yang kamu klaim sudah diterima oleh \"{$namaListing}\". Ambil di jam {$batasWaktu} WIB.",
            'is_read' => false,
        ]);
    }

    public static function pesananSelesai(int $userId, int $claimId, string $namaListing): void
    {
        Notification::create([
            'user_id' => $userId,
            'claim_id' => $claimId,
            'jenis' => 'pesanan_selesai',
            'judul' => 'Pesanan Siap Diambil',
            'pesan' => "Pesanan yang sudah kamu pesan di \"{$namaListing}\" Sudah siap untuk diambil",
            'is_read' => false,
        ]);
    }

    public static function menungguPembayaran(int $userId, int $claimId, string $namaListing): void
    {
        Notification::create([
            'user_id' => $userId,
            'claim_id' => $claimId,
            'jenis' => 'menunggu_pembayaran',
            'judul' => 'Menunggu Pembayaran',
            'pesan' => "Pesanan \"{$namaListing}\" berhasil dibuat. Yuk, selesaikan pembayaranmu sekarang!",
            'is_read' => false,
        ]);
    }

    public static function listingBaru(int $konsumenUserId, int $listingId, string $namaListing, string $namaMerchant): void
    {
        Notification::create([
            'user_id' => $konsumenUserId,
            'claim_id' => null,
            'jenis' => 'listing_baru',
            'judul' => 'Listing Baru',
            'pesan' => "\"{$namaListing}\" Stok Terbatas, Segera Klaim Pesanan Sebelum Kehabisan!",
            'is_read' => false,
        ]);
    }

    public static function tonggakDampak(int $konsumenUserId, float $totalKgCo2): void
    {
        Notification::create([
            'user_id' => $konsumenUserId,
            'claim_id' => null,
            'jenis' => 'tonggak_dampak',
            'judul' => 'Tonggak Dampak',
            'pesan' => "Anda telah menghemat {totalKgCo2} kg CO2 minggu ini. Anda termasuk dalam 5% penyelamat teratas di wilayah Anda!",
            'is_read' => false,
        ]);
    }

    public static function klaimMasuk(int $merchantUserId, int $claimId, string $namaKonsumen): void
    {
        Notification::create([
            'user_id' => $merchantUserId,
            'claim_id' => $claimId,
            'jenis' => 'claims_masuk',
            'judul' => 'Klaim Baru',
            'pesan' => "Pesanan #{$claimId} dari {namaKonsumen} sedang menunggu konfirmasi.",
            'is_read' => false,
        ]);
    }

    public static function listingExpired(int $merchantUserId, string $namaListing): void
    {
        Notification::create([
            'user_id' => $merchantUserId,
            'claim_id' => null,
            'jenis' => 'listing_expired',
            'judul' => 'Listing Ditutup Otomatis',
            'pesan' => "Listing \"{$namaListing}\" telah ditutup otomatis karena waktu pengambilan sudah habis.",
            'is_read' => false,
        ]);
    }

    public static function stokMenipis(int $merchantUserId, string $namaListing, int $sisaStok): void
    {
        Notification::create([
            'user_id' => $merchantUserId,
            'claim_id' => null,
            'jenis' => 'stok_menipis',
            'judul' => 'Stok Menipis',
            'pesan' => "Hanya tersisa {$sisaStok} '{$namaListing}' di listing Anda. Segera update stok!",
            'is_read' => false,
        ]);
    }
}
