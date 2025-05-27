// resources/views/auth/register.blade.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // Kolom username Anda
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Penting untuk Breeze (verifikasi email)
            $table->string('password');
            $table->string('role')->default('user');
            $table->rememberToken(); // Penting untuk Breeze (fitur "ingat saya")
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};