<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'id_pengguna',
        'id_claims',
        'jenis',
        'judul',
        'pesan',
        'is_read',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function claims()
    {
        return $this->belongsTo(
            Claim::class,
            'id_claims'
        );
    }
}
