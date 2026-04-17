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

// Get all fakultas
$stmt = $pdo->query("SELECT * FROM fakultas ORDER BY nama_fakultas");
$fakultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fakultas - Universitas Gua</title>
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
        .fakultas-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        .fakultas-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .fakultas-item a {
            text-decoration: none;
            color: #0d6efd;
            font-weight: bold;
        }
        .fakultas-item a:hover {
            color: #0b5ed7;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>
    <div class="card">
        <h1>Daftar Fakultas</h1>
        <p>Universitas Gua memiliki beberapa fakultas yang dapat dipilih mahasiswa.</p>
    </div>

    <div class="fakultas-list">
        <?php foreach ($fakultas as $fak): ?>
            <div class="fakultas-item">
                <h3><?php echo htmlspecialchars($fak['nama_fakultas']); ?></h3>
                <a href="jurusan.php?fakultas_id=<?php echo $fak['id']; ?>">Lihat Jurusan</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>