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

// Get all nilai for this user
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

// Calculate statistics
$total_nilai = 0;
$count = count($nilai);
$ipk = 0;

if ($count > 0) {
    foreach ($nilai as $n) {
        $total_nilai += $n['nilai'];
    }
    $ipk = round($total_nilai / $count, 2);
}

// Group by semester
$semesters = [];
foreach ($nilai as $n) {
    $sem = $n['semester'];
    if (!isset($semesters[$sem])) {
        $semesters[$sem] = [];
    }
    $semesters[$sem][] = $n;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip Akademik - Universitas Gua</title>
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
        .header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stats {
            background-color: #f0f4ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
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
        .semester-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>
    <div class="card">
        <div class="header-info">
            <div>
                <h1>Transkrip Akademik</h1>
                <p>Universitas Gua</p>
            </div>
            <div>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>NIM:</strong> <?php echo htmlspecialchars($user['id'] + 100000); // Dummy NIM ?></p>
            </div>
        </div>

        <div class="stats">
            <h3>Ringkasan Akademik</h3>
            <p><strong>Total Mata Kuliah:</strong> <?php echo $count; ?></p>
            <p><strong>IPK:</strong> <?php echo $ipk; ?></p>
        </div>
    </div>

    <?php foreach ($semesters as $sem => $nilai_sem): ?>
        <div class="card">
            <h2 class="semester-title">Semester: <?php echo htmlspecialchars($sem); ?></h2>
            <table>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Jurusan</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                </tr>
                <?php foreach ($nilai_sem as $n): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($n['nama_kelas']); ?></td>
                        <td><?php echo htmlspecialchars($n['dosen']); ?></td>
                        <td><?php echo htmlspecialchars($n['nama_jurusan']); ?></td>
                        <td><?php echo htmlspecialchars($n['nilai']); ?></td>
                        <td>
                            <?php
                            $grade = '';
                            if ($n['nilai'] >= 85) $grade = 'A';
                            elseif ($n['nilai'] >= 75) $grade = 'B';
                            elseif ($n['nilai'] >= 65) $grade = 'C';
                            elseif ($n['nilai'] >= 55) $grade = 'D';
                            else $grade = 'E';
                            echo $grade;
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endforeach; ?>

    <?php if (empty($nilai)): ?>
        <div class="card">
            <p>Belum ada data nilai yang tersedia.</p>
        </div>
    <?php endif; ?>
</body>
</html>