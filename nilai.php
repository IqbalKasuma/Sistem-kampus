<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

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

// Get nilai for this user
$stmt = $pdo->prepare("
    SELECT n.nilai, n.semester, k.nama_kelas, k.dosen, j.nama_jurusan, f.nama_fakultas
    FROM nilai n
    JOIN kelas k ON n.kelas_id = k.id
    JOIN jurusan j ON k.jurusan_id = j.id
    JOIN fakultas f ON j.fakultas_id = f.id
    WHERE n.user_id = :user_id
    ORDER BY n.semester DESC, k.nama_kelas
");
$stmt->execute([':user_id' => $user['id']]);
$nilai = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai - Universitas Gua</title>
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
        .grade {
            font-weight: bold;
        }
        .grade.A { color: #28a745; }
        .grade.B { color: #007bff; }
        .grade.C { color: #ffc107; }
        .grade.D { color: #fd7e14; }
        .grade.E { color: #dc3545; }
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>
    <div class="card">
        <h1>Nilai Akademik</h1>
        <p>Berikut adalah nilai-nilai kamu di Universitas Gua.</p>
    </div>

    <div class="card">
        <h2>Daftar Nilai</h2>
        <?php if (empty($nilai)): ?>
            <p>Belum ada nilai yang tersedia.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Jurusan</th>
                    <th>Fakultas</th>
                    <th>Semester</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                </tr>
                <?php foreach ($nilai as $n): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($n['nama_kelas']); ?></td>
                        <td><?php echo htmlspecialchars($n['dosen']); ?></td>
                        <td><?php echo htmlspecialchars($n['nama_jurusan']); ?></td>
                        <td><?php echo htmlspecialchars($n['nama_fakultas']); ?></td>
                        <td><?php echo htmlspecialchars($n['semester']); ?></td>
                        <td><?php echo htmlspecialchars($n['nilai']); ?></td>
                        <td>
                            <?php
                            $grade = '';
                            if ($n['nilai'] >= 85) $grade = 'A';
                            elseif ($n['nilai'] >= 75) $grade = 'B';
                            elseif ($n['nilai'] >= 65) $grade = 'C';
                            elseif ($n['nilai'] >= 55) $grade = 'D';
                            else $grade = 'E';
                            echo "<span class='grade $grade'>$grade</span>";
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>