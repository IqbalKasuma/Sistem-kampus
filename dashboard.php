<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Universitas Gua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #eef5fb;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .menu {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .menu h2 {
            margin-top: 0;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .menu-item {
            background-color: white;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .menu-item a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .menu-item a:hover {
            color: #4CAF50;
        }
        .logout {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
        .logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>Dashboard Mahasiswa</h1>
            <p>Selamat datang, <?php echo htmlspecialchars($user['name']); ?>, di Universitas Gua.</p>
        </div>
        <div style="text-align: right;">
            <?php if (!empty($user['profile_photo'])): ?>
                <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Foto Profil" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid #0d6efd;display:block;margin-bottom:10px;">
            <?php endif; ?>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="menu">
        <h2>Menu Kampus</h2>
        <div class="menu-grid">
            <div class="menu-item">
                <a href="ktm.php">Isi Form KTM</a>
                <p>Buat kartu identitas mahasiswa</p>
            </div>
            <div class="menu-item">
                <a href="fakultas.php">Fakultas & Jurusan</a>
                <p>Lihat daftar fakultas dan jurusan</p>
            </div>
            <div class="menu-item">
                <a href="kelas.php">Daftar Kelas</a>
                <p>Lihat kelas yang tersedia</p>
            </div>
            <div class="menu-item">
                <a href="nilai.php">Nilai Akademik</a>
                <p>Lihat nilai mata kuliah</p>
            </div>
            <div class="menu-item">
                <a href="transkrip.php">Transkrip Akademik</a>
                <p>Lihat ringkasan nilai dan IPK</p>
            </div>
            <div class="menu-item">
                <a href="attendance.php">Absensi</a>
                <p>Presensi masuk dan pulang</p>
            </div>
            <div class="menu-item">
                <a href="jadwal.php">Jadwal Kuliah</a>
                <p>Lihat jadwal dan informasi kelas</p>
            </div>
            <div class="menu-item">
                <a href="announcement.php">Berita Kampus</a>
                <p>Informasi dan pengumuman</p>
            </div>
            <div class="menu-item">
                <a href="profile.php">Profil Mahasiswa</a>
                <p>Lihat detail akun dan informasi</p>
            </div>
        </div>
    </div>

    <div class="menu">
        <h2>Informasi Akun</h2>
        <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Bergabung sejak:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>
</body>
</html>