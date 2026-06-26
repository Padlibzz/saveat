<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'no_telphone',
        'peran',
        'status',
        'profil_image',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    public function profil()
    {
        return $this->hasOne(Profil::class, 'user_id');
    }

    public function merchant()
    {
        return $this->hasOne(Profil::class, 'user_id')->where('tipe_profil', 'merchant');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'user_id');
    }
}
