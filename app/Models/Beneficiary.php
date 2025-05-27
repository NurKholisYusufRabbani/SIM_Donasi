<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'category', // Contoh: 'Panti Asuhan', 'Individu Miskin', 'Korban Bencana'
    ];

    // --- RELASI ---

    /**
     * Relasi: Satu Beneficiary bisa menerima banyak Distribusi.
     * beneficiaries 1:N distributions
     * (beneficiaries ||--o{ distributions : "receives")
     */
    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}