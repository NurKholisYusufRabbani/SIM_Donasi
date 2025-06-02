# Sistem Informasi Manajemen Donasi (SIM Donasi)

Sistem Informasi Manajemen Donasi (SIM Donasi) adalah aplikasi web yang dirancang untuk mengelola proses donasi, mulai dari pencatatan donatur, penerimaan donasi, hingga distribusi kepada penerima manfaat. Aplikasi ini dibangun menggunakan **Laravel** sebagai *framework* *backend*.

## 🚀 Fitur Utama

### Pengelolaan Donasi
* **Pencatatan Donasi**: Mencatat donasi uang atau barang dengan detail jumlah/deskripsi barang, tanggal, dan status (pending, diterima, ditolak).
* **Donatur**: Mengelola profil donatur yang terkait dengan akun pengguna.
* **Penerima Donasi (Beneficiaries)**: Mengelola data individu atau lembaga yang menerima distribusi donasi.

### Pengelolaan Distribusi
* **Distribusi Donasi**: Mencatat rincian distribusi donasi kepada penerima, termasuk jumlah dan siapa yang mendistribusikan.

### Manajemen Pengguna
* **Otentikasi**: Sistem login dan registrasi pengguna (dengan Laravel Breeze).
* **Roles**: Pengguna memiliki role (`admin`, `donator`, `user`, `distributor`).
    * **Admin**: Memiliki akses penuh ke seluruh fitur pengelolaan (donasi, distribusi, beneficiaries, pengguna).
    * **Donatur**: Dapat melihat dan membuat donasi yang mereka berikan, serta melengkapi profil donatur mereka.
    * **User**: Role default.
    * **Distributor**: Dapat melihat riwayat dan melakukan distribusi kepada beneficiary.

## 🛠️ Persyaratan Sistem (Backend)

Pastikan lingkungan pengembangan Anda memenuhi persyaratan Laravel:

* PHP >= 8.1
* Composer
* Database (MySQL direkomendasikan)
* Node.js & npm (untuk frontend dependencies Breeze)

## 📦 Instalasi (Backend)

Ikuti langkah-langkah di bawah ini untuk menyiapkan lingkungan *backend*:

1.  **Clone Repository**:
    ```bash
    git clone <URL_REPOSITORY_ANDA>
    cd sim-donasi
    ```

2.  **Instal Dependensi Composer**:
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**:
    * Buat file `.env` dari `.env.example`:
        ```bash
        cp .env.example .env
        ```
    * Generate application key:
        ```bash
        php artisan key:generate
        ```
    * Edit file `.env` dan konfigurasi database Anda:
        ```ini
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=sim_donasi # Ganti dengan nama database Anda
        DB_USERNAME=root      # Ganti dengan username database Anda
        DB_PASSWORD=          # Ganti dengan password database Anda
        ```

4.  **Jalankan Migrasi Database**:
    Ini akan membuat tabel-tabel database yang diperlukan.
    ```bash
    php artisan migrate
    ```

5.  **Jalankan Database Seeder (Opsional tapi Direkomendasikan)**:
    Ini akan mengisi database dengan beberapa data awal, termasuk user admin.
    ```bash
    php artisan db:seed
    ```
    * **Login Admin**: Anda bisa login dengan `admin@example.com` dan password `password123`.
    * **Login Donatur**: `donator@example.com` dan password `password123`.
    * **Login User Biasa**: `user@example.com` dan password `password123`.
    *(Pastikan Anda telah membuat `AdminUserSeeder.php` seperti yang dijelaskan di diskusi sebelumnya)*

6.  **Instal Dependensi NPM & Kompilasi Frontend Assets (Breeze)**:
    ```bash
    npm install
    npm run dev # Untuk development, atau npm run build untuk produksi
    ```

7.  **Jalankan Server Lokal Laravel**:
    ```bash
    php artisan serve
    ```
    Aplikasi akan tersedia di `http://127.0.0.1:8000` (atau port lain).

## 🔑 Otentikasi dan Otorisasi

* **Login/Register**: Menggunakan Laravel Breeze.
* **Role-Based Access Control**:
    * Backend menerapkan validasi berdasarkan role pengguna (`admin`, `donator`, `user`, `distributor`).
    * Pastikan *frontend* Anda juga memvalidasi dan menyembunyikan/menampilkan elemen UI berdasarkan role pengguna yang sedang login.
    * Objek `Auth::user()` tersedia di *backend* untuk mendapatkan informasi pengguna yang sedang login, termasuk `role`.
