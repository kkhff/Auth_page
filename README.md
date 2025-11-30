# Auth-page
Halaman login sederhana yang menggunakan PHP Native (tanpa framework) dan Bootstrap sebagai tampilannya.

## Instalasi dan Deployment Cepat (Docker)
Proyek ini menggunakan Docker untuk menyediakan lingkungan pengembangan yang konsisten (PHP + Apache + Database).

**Persyaratan**  
Pastikan Anda telah menginstal:
- Docker
- Docker Compose

**Cara Menjalankan**
1. **Kloning Repositori:**
    ``` Bash
    https://github.com/kkhff/Auth_page.git
    cd auth_page
    ```
2. **Siapkan Konfigurasi Lingkungan:** Salin file template lingkungan dan sesuaikan nilainya:

    ``` Bash
    cp .env.example .env
    ```
    **Penting:** Edit file .env dan ganti variabel DB_PASSWORD, DB_DATABASE, dan DB_USERNAME dengan kredensial yang Anda inginkan.

3. **Bangun dan Jalankan Kontainer:** Perintah ini akan membangun image dan menjalankan layanan PHP/Apache serta database (MySQL/MariaDB) di latar belakang.

    ``` Bash
    docker-compose up -d --build
    ```
4. **Akses Aplikasi:** Aplikasi akan tersedia di browser Anda: Ganti dengan alamat yang benar, misalnya: `http://localhost:8080` (untuk tampilan web) dan `http://localhost:8081` (untuk phpmyadmin)

## Konfigurasi Database
Proyek ini membutuhkan database untuk menyimpan data pengguna.

1. **Kredensial Database**

    Pastikan file .env telah diatur dengan benar:
    ``` Ini, TOML
    DB_HOST=auth-db
    DB_ROOT_PASS=[Root Password Anda]
    DB_USER=[Username Anda]
    DB_PASS=[Password Anda]
    DB_NAME=[Nama Database Anda]
    ```
2. **Skema Tabel**

    Proyek ini dirancang untuk **membuat tabel** `users` **secara otomatis** saat aplikasi pertama kali dijalankan, berdasarkan skema yang didefinisikan dalam `app/config/database.php`.

    Namun, untuk referensi manual, atau jika Anda ingin mengimpor tabel secara langsung, berikut adalah skema SQL yang digunakan:
    ``` SQL
    CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(225) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ```
    **Detail kolom Kunci**
    | Kolom | Tipe Data | Keterangan |
    | :--- | :--- | :--- |
    |`id`|`INT UNSIGNED`|Kunci utama dan auto-increment.|
    |`username`|`VARCHAR(50)`|Nama pengguna unik untuk login.|
    |`email`|`VARCHAR(100)`|Alamat email unik.|
    |`password`|`VARCHAR(225)`|Wajib: Hash kata sandi (hash panjang diperlukan untuk keamanan, misal menggunakan password_hash).|
    |`created_at`|`TIMESTAMP`|Waktu pendaftaran.|

## Struktur File
| File/Direktori | Peran Utama | Deskripsi |
| :--- | :--- | :--- |
| `public/index.php` |**Titik Masuk (Front Controller)**|File utama yang menerima semua permintaan (request). Tugasnya adalah memuat konfigurasi (`database.php`) dan mengarahkan permintaan ke controller yang sesuai.| 
| `app/config/database.php` |**Konfigurasi & Koneksi DB**|Berisi kredensial database (dari `.env`) dan logika untuk membuat atau membangun koneksi ke database. Juga berisi skema otomatisasi tabel `users`.| 
| `app/models/userModel.php`|**Interaksi Database (Model)**|Kelas atau fungsi yang khusus menangani data. **Tugasnya meliputi:** Mencari pengguna, menyimpan pengguna baru (setelah *hashing*), dan mengambil data pengguna.| 
| `app/controllers/authController.php` |**Logika Bisnis (Autentikasi)**|Menangani semua logika **login, register, dan logout.** Termasuk menerima data formulir, memverifikasi kredensial, dan mengelola status sesi (`$_SESSION`).| 
| `views/auth/auth.php` |**Tampilan Otentikasi**|Berisi markup HTML untuk formulir **Login** dan **Registrasi**.| 
| `views/dashboard/dashboard.php` |**Tampilan Halaman Utama**|Berisi markup HTML dari halaman yang ditampilkan **setelah login berhasil**. Halaman ini harus selalu diawali dengan pemeriksaan sesi.| 
| `views/layout/header.php & footer.php` |**Komponen Layout**|Berisi elemen UI yang berulang di setiap halaman, seperti navigation bar atau `<head>` HTML, untuk konsistensi tampilan.| 

## PERHATIAN PENTING: INISIALISASI ULANG DATABASE

Karena adanya perubahan pada kredensial default database, jika Anda sebelumnya pernah menjalankan proyek ini, Anda MUNGKIN perlu menghapus volume database lokal Anda untuk menghindari error `Access Denied` atau `Connection refused`.

**Hapus Volume Data (Lakukan ini jika Anda mendapatkan error koneksi):**

1. **Hentikan dan Hapus Container yang Berjalan**

    Pastikan semua container yang terkait dengan proyek ini dihentikan dan dihapus, termasuk volume-nya:

    ``` Bash
    docker-compose down -v
    ```
2. **Jalankan Ulang Proyek**

    Setelah volume dihapus, jalankan ulang proyek. MySQL akan memulai dari awal dan menggunakan variabel `$DB_USER` (misalnya, `sayadmin`) dan `$DB_PASS` (misalnya, `onlyuser`) dari file `.env` Anda untuk inisialisasi.

    ``` Bash
    docker-compose up --build -d
    ```
