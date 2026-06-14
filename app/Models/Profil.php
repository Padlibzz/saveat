<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profil extends Model
{
    use HasFactory;

    // 1. UBAH: Sesuaikan nama tabel dengan yang ada di database (tambah huruf 's')
    protected $table = 'profils';

    // 2. UBAH: Bersihkan duplikasi dan hapus 'id_pengguna' yang sudah tidak dipakai
    protected $fillable = [
        'user_id',
        'tipe_profil',
        'alamat',
        'nama_usaha',
        'deskripsi',
        'link_map',
        'status_verifikasi',
        'diverifikasi_oleh',
        'alasan_penolakan',
    ];

    // 3. UBAH: Nama relasi diubah dari pengguna() menjadi user() dan memakai user_id
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function isKonsumen(): bool
    {
        return $this->tipe_profil === 'konsumen';
    }

    public function isMerchant(): bool
    {
        return $this->tipe_profil === 'merchant';
    }

    public function isAdmin(): bool
    {
        return $this->tipe_profil === 'admin';
    }
}