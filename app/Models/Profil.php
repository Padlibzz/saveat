<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $fillable = [
        'user_id',
        'tipe_profil',
        'nama_usaha',
        'alamat',
        'deskripsi',
        'link_map',
        'status_verifikasi',
        'diverifikasi_oleh',
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
