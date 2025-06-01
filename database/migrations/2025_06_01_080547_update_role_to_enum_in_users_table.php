<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Definisi nilai enum yang diinginkan
            $roles = ['distributor', 'admin', 'donatur', 'user'];
            $defaultRole = 'user';

            // Mengubah kolom 'role' menjadi enum dengan nilai yang telah ditentukan
            $table->enum('role', $roles)->default($defaultRole)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan kolom 'role' ke tipe string seperti semula
            // Defaultnya juga dikembalikan ke 'user'
            $table->string('role')->default('user')->change();
        });
    }
};
