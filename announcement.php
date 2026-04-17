<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Kampus - Universitas Gua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f6f9ff;
        }
        .top-link {
            margin-bottom: 20px;
            display: block;
            color: #0d6efd;
            text-decoration: none;
        }
        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>
    <div class="card">
        <h1>Pengumuman Kampus</h1>
        <p>Berikut beberapa info terbaru untuk mahasiswa Universitas Gua.</p>
    </div>

    <div class="card">
        <h2>Pengumuman 1</h2>
        <p>Pendaftaran ulang semester genap dibuka mulai 1 Mei sampai 10 Mei.</p>
    </div>

    <div class="card">
        <h2>Pengumuman 2</h2>
        <p>Workshop pemrograman web akan diadakan pada tanggal 15 Mei di aula utama.</p>
    </div>

    <div class="card">
        <h2>Pengumuman 3</h2>
        <p>Jangan lupa mengisi form KTM jika belum memiliki identitas mahasiswa.</p>
    </div>
</body>
</html>