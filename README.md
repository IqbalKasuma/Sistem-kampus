# Sistem Kampus Sederhana

Aplikasi web sederhana untuk manajemen mahasiswa dengan fitur registrasi, login, upload foto profil, dan sistem absensi.

## Fitur Utama

- **Registrasi Mahasiswa** - Daftar akun dengan validasi email
- **Login** - Autentikasi dengan email dan password
- **Dashboard** - Menu utama setelah login
- **Profil Mahasiswa** - Lihat detail akun dan upload foto profil
- **Sistem Absensi** - Check-in dan check-out presensi harian
- **Form Submission** - Contoh form dengan berbagai tipe input

## Teknologi yang Digunakan

- **PHP 8.x** - Server-side scripting
- **MySQL/MariaDB** - Database relasional
- **HTML/CSS** - Frontend
- **PDO** - PHP Data Objects untuk database connection
- **Session** - User authentication management

## Struktur File

```
project/
├── register.php          # Halaman registrasi mahasiswa
├── login.php             # Halaman login
├── dashboard.php         # Dashboard utama
├── profile.php           # Profil mahasiswa + upload foto
├── attendance.php        # Sistem absensi (check-in/check-out)
├── form.php              # Contoh form umum
├── logout.php            # Logout
├── config.php            # Konfigurasi database
├── uploads/              # Folder penyimpanan foto profil
└── README.md             # File ini
```

## Cara Install dan Menjalankan

### 1. Setup Database

Buka phpMyAdmin atau MySQL CLI dan jalankan script berikut:

```sql
CREATE DATABASE latihanform;
USE latihanform;

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    profile_photo VARCHAR(255)
);

-- Tabel Attendance
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    attendance_date DATE,
    check_in_time TIME,
    check_out_time TIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel Form Data (untuk form.php)
CREATE TABLE form_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    gender VARCHAR(50),
    country VARCHAR(100),
    interests VARCHAR(255),
    message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 2. Copy Project ke XAMPP

1. Copy semua file `.php` ke folder `C:\xampp\htdocs\` (atau subfolder project Anda)
2. Pastikan folder `uploads/` ada (akan otomatis dibuat saat upload foto pertama)

### 3. Jalankan XAMPP

1. Buka XAMPP Control Panel
2. Jalankan **Apache** dan **MySQL**
3. Buka browser dan akses: `http://localhost/register.php`

## Cara Menggunakan

### Alur User Flow

1. **Registrasi**
   - Buka `register.php`
   - Isi nama, email, password
   - Klik "Daftar"

2. **Login**
   - Masukkan email dan password
   - Klik "Login"
   - Otomatis masuk ke dashboard

3. **Dashboard**
   - Lihat menu pilihan fitur
   - Klik salah satu menu untuk akses fitur

4. **Upload Foto Profil**
   - Klik menu "Profil Mahasiswa"
   - Scroll ke bawah section "Upload Foto Profil"
   - Pilih file foto (JPG, PNG, GIF)
   - Klik "Upload Foto"

5. **Absensi**
   - Klik menu "Absensi"
   - Tekan "Check In" saat masuk
   - Tekan "Check Out" saat pulang
   - Lihat riwayat presensi 7 hari terakhir

## Penjelasan Teknologi Penting

### Session & Authentication
- File menggunakan `session_start()` untuk mempertahankan login
- Data user disimpan di `$_SESSION['user']`
- Setiap halaman yang dilindungi mengecek session terlebih dahulu

### Database Connection (PDO)
- Menggunakan PDO untuk keamanan (SQL Injection Prevention)
- Prepared statements dengan parameter binding (`:email`, `:id`, dll)
- Charset UTF-8 untuk mendukung karakter lokal

### Password Security
- Password di-hash dengan `password_hash()` sebelum disimpan
- Saat login, verifikasi dengan `password_verify()`
- Password tidak pernah disimpan dalam plain text

### File Upload
- Validasi tipe file dengan `mime_content_type()`
- Cek ukuran file maksimal 2MB
- Buat nama file unik dengan `time()` dan user ID
- Folder `uploads/` dibuat otomatis jika belum ada

## Struktur Database

### Tabel: users
| Field | Type | Keterangan |
|-------|------|-----------|
| id | INT | Primary Key, Auto Increment |
| name | VARCHAR(100) | Nama mahasiswa |
| email | VARCHAR(100) | Email unik |
| password | VARCHAR(255) | Password ter-hash |
| created_at | DATETIME | Waktu daftar |
| profile_photo | VARCHAR(255) | Path foto profil |

### Tabel: attendance
| Field | Type | Keterangan |
|-------|------|-----------|
| id | INT | Primary Key |
| user_id | INT | Foreign Key ke users |
| attendance_date | DATE | Tanggal presensi |
| check_in_time | TIME | Jam masuk |
| check_out_time | TIME | Jam pulang |
| created_at | DATETIME | Waktu record dibuat |

## File Penting untuk Dipahami

### register.php
- Validasi input: nama, email, password
- Cek email sudah terdaftar atau belum
- Hash password sebelum simpan ke DB

### login.php
- Ambil email dan password dari form
- Query user berdasarkan email
- Verifikasi password dengan hash di DB
- Buat session jika login berhasil

### profile.php
- Ambil data user dari session
- Query DB untuk data user terbaru
- Handle file upload foto
- Update kolom `profile_photo` di tabel users

### attendance.php
- Check-in: cek apakah sudah ada record hari ini
- Check-out: cek sudah check-in tapi belum check-out
- Query riwayat 7 hari terakhir

## Requirements

- PHP 8.0 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3
- XAMPP atau server lokal lainnya
- Browser modern (Chrome, Firefox, dll)

## Catatan Keamanan

- Di production, gunakan password database yang kuat
- Jangan simpan `config.php` di repository public
- Validasi semua input dari user
- Gunakan HTTPS untuk koneksi yang aman
- Sanitasi output dengan `htmlspecialchars()`

## Pengembangan Selanjutnya

Fitur yang bisa ditambahkan:
- Admin panel
- Hamburger menu dengan styling CSS modern
- Email verification saat registrasi
- Password reset
- Pagination untuk riwayat absensi
- View profil mahasiswa lain
- Sistem notifikasi

## Author

Dibuat sebagai project pembelajaran PHP dan MySQL.

## License

Bebas digunakan untuk keperluan personal dan pembelajaran.
