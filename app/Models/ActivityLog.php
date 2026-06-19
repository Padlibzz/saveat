<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'aksi',
        'deskripsi',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function catat($user_id, $aksi, $deskripsi = null)
    {
        return self::create([
            'user_id' => $user_id,
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
