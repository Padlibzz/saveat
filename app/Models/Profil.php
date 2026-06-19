<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profil extends Model
{
    use HasFactory;

    protected $table = 'profils';

    protected $fillable = [
        'user_id',
        'tipe_profil',
        'nama_usaha',
        'alamat',
        'latitude',
        'longitude',
        'izin_lokasi',
        'deskripsi',
        'link_map',
        'status_verifikasi',
        'diverifikasi_oleh',
        'alasan_penolakan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'merchant_id');
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
