<?php
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

$fakultas_id = $_GET['fakultas_id'] ?? null;
if (!$fakultas_id) {
    header("Location: fakultas.php");
    exit;
}

// Get fakultas name
$stmt = $pdo->prepare("SELECT nama_fakultas FROM fakultas WHERE id = :id");
$stmt->execute([':id' => $fakultas_id]);
$fakultas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fakultas) {
    header("Location: fakultas.php");
    exit;
}

// Get jurusan for this fakultas
$stmt = $pdo->prepare("SELECT * FROM jurusan WHERE fakultas_id = :fakultas_id ORDER BY nama_jurusan");
$stmt->execute([':fakultas_id' => $fakultas_id]);
$jurusan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurusan - Universitas Gua</title>
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
            margin-bottom: 15px;
        }
        .jurusan-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }
        .jurusan-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
        }
        .jurusan-item h3 {
            margin-top: 0;
        }
        .jurusan-item a {
            text-decoration: none;
            color: #0d6efd;
            font-weight: bold;
        }
        .jurusan-item a:hover {
            color: #0b5ed7;
        }
    </style>
</head>
<body>
    <a href="fakultas.php" class="top-link">&larr; Kembali ke Fakultas</a>
    <div class="card">
        <h1>Jurusan Fakultas <?php echo htmlspecialchars($fakultas['nama_fakultas']); ?></h1>
        <p>Berikut adalah jurusan yang tersedia di Fakultas <?php echo htmlspecialchars($fakultas['nama_fakultas']); ?>.</p>
    </div>

    <div class="jurusan-list">
        <?php foreach ($jurusan as $jur): ?>
            <div class="jurusan-item">
                <h3><?php echo htmlspecialchars($jur['nama_jurusan']); ?></h3>
                <a href="kelas.php?jurusan_id=<?php echo $jur['id']; ?>">Lihat Kelas</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>