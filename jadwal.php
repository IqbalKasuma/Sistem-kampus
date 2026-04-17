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
    <title>Jadwal Kuliah - Universitas Gua</title>
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f0f4ff;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>
    <div class="card">
        <h1>Jadwal Kuliah</h1>
        <p>Ini contoh jadwal kuliah untuk mahasiswa Universitas Gua.</p>
        <table>
            <tr>
                <th>Hari</th>
                <th>Matakuliah</th>
                <th>Jam</th>
                <th>Ruang</th>
            </tr>
            <tr>
                <td>Senin</td>
                <td>Basis Data</td>
                <td>08:00 - 10:00</td>
                <td>Ruang 101</td>
            </tr>
            <tr>
                <td>Selasa</td>
                <td>Algoritma</td>
                <td>10:30 - 12:00</td>
                <td>Ruang 205</td>
            </tr>
            <tr>
                <td>Rabu</td>
                <td>Jaringan Komputer</td>
                <td>13:00 - 15:00</td>
                <td>Lab 3</td>
            </tr>
            <tr>
                <td>Kamis</td>
                <td>Pemrograman Web</td>
                <td>09:00 - 11:00</td>
                <td>Ruang 202</td>
            </tr>
            <tr>
                <td>Jumat</td>
                <td>Sistem Operasi</td>
                <td>14:00 - 16:00</td>
                <td>Ruang 110</td>
            </tr>
        </table>
    </div>
</body>
</html>