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
* **Roles**: Pengguna memiliki role (`admin`, `donator`, `user`).
    * **Admin**: Memiliki akses penuh ke seluruh fitur pengelolaan (donasi, distribusi, beneficiaries, pengguna).
    * **Donatur**: Dapat melihat dan membuat donasi yang mereka berikan, serta melengkapi profil donatur mereka.
    * **User**: Role dasar.

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

## 🗺️ Struktur API (Routes & Controller)

Sebagai *developer* *frontend*, penting untuk memahami *endpoints* yang tersedia. Aplikasi ini menggunakan rute berbasis resource untuk sebagian besar manajemen data.

Semua rute di bawah `Route::middleware('auth')` memerlukan **otentikasi pengguna** (login).

### Umum (Akses oleh User Terautentikasi - `user`, `donator`, `admin`)

| Resource      | Method | URL                      | Route Name                | Controller Action | Keterangan                                   |
| :------------ | :----- | :----------------------- | :------------------------ | :---------------- | :------------------------------------------- |
| **Donations** | GET    | `/donations`             | `donations.index`         | `index`           | Daftar donasi (semua untuk admin, miliknya untuk donatur) |
|               | POST   | `/donations`             | `donations.store`         | `store`           | Simpan donasi baru                           |
|               | GET    | `/donations/create`      | `donations.create`        | `create`          | Form pembuatan donasi                        |
|               | GET    | `/donations/{donation}`  | `donations.show`          | `show`            | Detail donasi (otorisasi di controller)      |
|               | PUT/PATCH| `/donations/{donation}` | `donations.update`        | `update`          | Update donasi (hanya admin)                  |
|               | DELETE | `/donations/{donation}`  | `donations.destroy`       | `destroy`         | Hapus donasi (hanya admin)                   |
|               | GET    | `/donations/{donation}/edit`| `donations.edit`       | `edit`            | Form edit donasi (hanya admin)               |
| **Donors** | GET    | `/donors`                | `donors.index`            | `index`           | Daftar donor (admin), Profil donor sendiri (donatur) |
|               | POST   | `/donors`                | `donors.store`            | `store`           | Buat/lengkapi profil donor                   |
|               | GET    | `/donors/create`         | `donors.create`           | `create`          | Form pembuatan/lengkapi profil donor         |
|               | GET    | `/donors/{donor}`        | `donors.show`             | `show`            | Detail profil donor (admin atau miliknya)    |
|               | PUT/PATCH| `/donors/{donor}`       | `donors.update`           | `update`          | Update profil donor                          |
|               | GET    | `/donors/{donor}/edit`   | `donors.edit`             | `edit`            | Form edit profil donor                       |
|               | DELETE | `/donors/{donor}`        | `donors.destroy`          | `destroy`         | Hapus profil donor (hanya admin)             |
| **Distributions**| GET | `/distributions`         | `distributions.index`     | `index`           | Daftar distribusi (semua untuk admin, miliknya untuk distributor) |
|               | POST   | `/distributions`         | `distributions.store`     | `store`           | Simpan distribusi baru                       |
|               | GET    | `/distributions/create`  | `distributions.create`    | `create`          | Form pembuatan distribusi                    |
|               | GET    | `/distributions/{distribution}`| `distributions.show` | `show`            | Detail distribusi (otorisasi di controller)  |
|               | PUT/PATCH| `/distributions/{distribution}`| `distributions.update`| `update`          | Update distribusi (hanya admin)              |
|               | DELETE | `/distributions/{distribution}`| `distributions.destroy`| `destroy`         | Hapus distribusi (hanya admin)               |
|               | GET    | `/distributions/{distribution}/edit`| `distributions.edit`| `edit`            | Form edit distribusi (hanya admin)           |

### Khusus Admin (`Route::middleware(['auth', 'admin'])`)

| Resource      | Method | URL                      | Route Name                | Controller Action | Keterangan                                   |
| :------------ | :----- | :----------------------- | :------------------------ | :---------------- | :------------------------------------------- |
| **Beneficiaries**| GET | `/beneficiaries`         | `beneficiaries.index`     | `index`           | Daftar penerima donasi                       |
|               | POST   | `/beneficiaries`         | `beneficiaries.store`     | `store`           | Simpan penerima donasi baru                  |
|               | GET    | `/beneficiaries/create`  | `beneficiaries.create`    | `create`          | Form pembuatan penerima donasi               |
|               | GET    | `/beneficiaries/{beneficiary}`| `beneficiaries.show` | `show`            | Detail penerima donasi                       |
|               | PUT/PATCH| `/beneficiaries/{beneficiary}`| `beneficiaries.update`| `update`          | Update penerima donasi                       |
|               | DELETE | `/beneficiaries/{beneficiary}`| `beneficiaries.destroy`| `destroy`         | Hapus penerima donasi                        |
|               | GET    | `/beneficiaries/{beneficiary}/edit`| `beneficiaries.edit`| `edit`            | Form edit penerima donasi                    |
| **Users** | GET    | `/users`                 | `users.index`             | `index`           | Daftar semua pengguna sistem                 |
|               | GET    | `/users/{user}`          | `users.show`              | `show`            | Detail pengguna                              |
|               | PUT/PATCH| `/users/{user}`         | `users.update`            | `update`          | Update profil pengguna (tidak termasuk password) |
|               | GET    | `/users/{user}/edit`     | `users.edit`              | `edit`            | Form edit pengguna                           |
|               | PATCH  | `/users/{user}/update-role`| `users.updateRole`      | `updateRole`      | Endpoint untuk mengubah role pengguna        |
|               | DELETE | `/users/{user}`          | `users.destroy`           | `destroy`         | Hapus pengguna                               |

## 🔑 Otentikasi dan Otorisasi

* **Login/Register**: Menggunakan Laravel Breeze.
* **Role-Based Access Control**:
    * Backend menerapkan validasi berdasarkan role pengguna (`admin`, `donator`, `user`).
    * Pastikan *frontend* Anda juga memvalidasi dan menyembunyikan/menampilkan elemen UI berdasarkan role pengguna yang sedang login.
    * Objek `Auth::user()` tersedia di *backend* untuk mendapatkan informasi pengguna yang sedang login, termasuk `role`.

## 🤝 Kontribusi Frontend

* Jika Anda memiliki pertanyaan tentang *backend* atau memerlukan *endpoint* tambahan, silakan hubungi *backend*.
* Laporkan setiap masalah atau *bug* yang Anda temukan.

---

Semoga README ini membantu *developer* *frontend*!
