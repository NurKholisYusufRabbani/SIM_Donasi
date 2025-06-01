<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'distributed_by', // Foreign key ke users.id
        'distributed_at',
        'amount',
        'description',
        'date', // Mengganti 'distributed_at' jadi 'date' untuk konsistensi. Jika tidak, bisa tetap distributed_at.
        'beneficiary_id',
    ];

    protected $casts = [
        'date' => 'datetime', // Mengubah 'date' menjadi objek DateTime
        'amount' => 'decimal:2',
    ];

    // --- RELASI ---

    /**
     * Relasi: Satu Distribusi berasal dari satu Donasi.
     * distributions N:1 donations
     * (donations ||--o{ distributions : "is_distributed_as" - dibalik)
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Relasi: Satu Distribusi dilakukan oleh satu User (distributor).
     * distributions N:1 users
     * (users ||--o{ distributions : "distributes" - dibalik)
     */
    public function distributor()
    {
        // Parameter kedua adalah foreign key di tabel 'distributions' yang merujuk ke 'users.id'
        return $this->belongsTo(User::class, 'distributed_by');
    }

    /**
     * Relasi: Satu Distribusi diterima oleh satu Beneficiary.
     * distributions N:1 beneficiaries
     * (beneficiaries ||--o{ distributions : "receives" - dibalik)
     */
    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}