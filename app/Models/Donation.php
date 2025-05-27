<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'amount',
        'date',
        'type', // 'uang' atau 'barang'
        'item_details', // Detail barang jika donasi='barang'
        'description',
        'status', // 'pending', 'diterima', 'ditolak'
    ];

    protected $casts = [
        'date' => 'datetime', // Mengubah 'date' menjadi objek DateTime
        'amount' => 'decimal:2', // Memastikan 'amount' dibaca sebagai desimal dengan 2 angka di belakang koma
    ];

    // --- RELASI ---

    /**
     * Relasi: Satu Donasi dibuat oleh satu Donor.
     * donations N:1 donors
     * (donors ||--o{ donations : "makes" - dibalik)
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Relasi: Satu Donasi bisa memiliki banyak Distribusi.
     * (donations ||--o{ distributions : "is_distributed_as")
     */
    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}