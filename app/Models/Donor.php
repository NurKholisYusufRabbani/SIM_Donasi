<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'occupation',
    ];

    // --- RELASI ---

    /**
     * Relasi: Satu Donor dimiliki oleh satu User.
     * donors N:1 users
     * (users ||--o{ donors : "has" - dibalik)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu Donor bisa membuat banyak Donasi.
     * donors 1:N donations
     * (donors ||--o{ donations : "makes")
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}