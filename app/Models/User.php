<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Menggunakan Authenticatable untuk fitur login
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Atribut yang boleh diisi secara massal.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role', // Contoh: 'user', 'admin', 'donator'
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Atribut yang disembunyikan saat dikonversi ke array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * Mengubah tipe data atribut saat diakses.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel akan otomatis hash password
    ];

    // --- RELASI ---

    /**
     * Relasi: Satu User bisa memiliki satu profil Donor.
     * users 1:1 donors
     * (users ||--o{ donors : "has")
     */
    public function donor()
    {
        return $this->hasOne(Donor::class);
    }

    /**
     * Relasi: Satu User bisa melakukan banyak Distribusi.
     * (users ||--o{ distributions : "distributes")
     */
    public function distributions()
    {
        // Parameter kedua adalah foreign key di tabel 'distributions' yang merujuk ke 'users.id'
        return $this->hasMany(Distribution::class, 'distributed_by');
    }
}