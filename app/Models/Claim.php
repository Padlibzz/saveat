<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'user_id',
        'id_listings',
        'jumlah',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function listings()
    {
        return $this->belongsTo(Listing::class, 'id_listings');
    }
}