s<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$dbHost = 'localhost';
$dbName = 'latihanform';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$jurusan_id = $_GET['jurusan_id'] ?? null;
if (!$jurusan_id) {
    header("Location: fakultas.php");
    exit;
}

// Get jurusan name
$stmt = $pdo->prepare("SELECT j.nama_jurusan, f.nama_fakultas FROM jurusan j JOIN fakultas f ON j.fakultas_id = f.id WHERE j.id = :id");
$stmt->execute([':id' => $jurusan_id]);
$jurusan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$jurusan) {
    header("Location: fakultas.php");
    exit;
}

// Get kelas for this jurusan
$stmt = $pdo->prepare("SELECT * FROM kelas WHERE jurusan_id = :jurusan_id ORDER BY nama_kelas");
$stmt->execute([':jurusan_id' => $jurusan_id]);
$kelas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas - Universitas Gua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f0f4ff;
        }
        .kelas-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <a href="jurusan.php?fakultas_id=<?php echo $jurusan['fakultas_id'] ?? ''; ?>" class="top-link">&larr; Kembali ke Jurusan</a>
    <div class="card">
        <h1>Kelas Jurusan <?php echo htmlspecialchars($jurusan['nama_jurusan']); ?></h1>
        <p>Fakultas: <?php echo htmlspecialchars($jurusan['nama_fakultas']); ?></p>
    </div>

    <div class="card">
        <h2>Daftar Kelas</h2>
        <?php if (empty($kelas)): ?>
            <p>Belum ada kelas yang tersedia untuk jurusan ini.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Dosen</th>
                    <th>Jadwal</th>
                </tr>
                <?php foreach ($kelas as $k): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($k['nama_kelas']); ?></td>
                        <td><?php echo htmlspecialchars($k['dosen']); ?></td>
                        <td><?php echo htmlspecialchars($k['jadwal']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>