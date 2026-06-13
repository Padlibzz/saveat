<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',   // <-- PERBAIKAN
        'claim_id',  // <-- PERBAIKAN
        'user_id',   
        'claim_id',  
        'jenis',
        'judul',
        'pesan',
        'is_read',
    ];

    public function user() // <-- PERBAIKAN (singular)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function claim() // <-- PERBAIKAN (singular)
    public function claim() 
    {
        return $this->belongsTo(Claim::class, 'claim_id');
    }
}