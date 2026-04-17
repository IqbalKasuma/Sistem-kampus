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

$today = date('Y-m-d');
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'check_in') {
        $stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = :user_id AND attendance_date = :date");
        $stmt->execute([':user_id' => $user['id'], ':date' => $today]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            $message = 'Kamu sudah melakukan presensi hari ini.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO attendance (user_id, attendance_date, check_in_time) VALUES (:user_id, :date, :time)");
            $stmt->execute([
                ':user_id' => $user['id'],
                ':date' => $today,
                ':time' => date('H:i:s'),
            ]);
            $message = 'Presensi masuk berhasil.';
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'check_out') {
        $stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = :user_id AND attendance_date = :date");
        $stmt->execute([':user_id' => $user['id'], ':date' => $today]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$record || $record['check_in_time'] === null) {
            $message = 'Kamu belum melakukan check-in hari ini.';
        } elseif ($record['check_out_time'] !== null) {
            $message = 'Kamu sudah melakukan check-out hari ini.';
        } else {
            $stmt = $pdo->prepare("UPDATE attendance SET check_out_time = :time WHERE id = :id");
            $stmt->execute([':time' => date('H:i:s'), ':id' => $record['id']]);
            $message = 'Presensi pulang berhasil.';
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = :user_id AND attendance_date = :date");
$stmt->execute([':user_id' => $user['id'], ':date' => $today]);
$todayRecord = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = :user_id ORDER BY attendance_date DESC LIMIT 7");
$stmt->execute([':user_id' => $user['id']]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - Universitas Gua</title>
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
        .buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .buttons button {
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
        }
        .buttons button:hover {
            background-color: #0b5ed7;
        }
        .notice {
            margin-bottom: 15px;
            color: #0d6efd;
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
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>
    <div class="card">
        <h1>Absensi Mahasiswa</h1>
        <p>Hari ini: <?php echo date('d M Y'); ?></p>

        <?php if ($message): ?>
            <div class="notice"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="buttons">
            <form method="POST" style="margin:0;">
                <input type="hidden" name="action" value="check_in">
                <button type="submit">Check In</button>
            </form>
            <form method="POST" style="margin:0;">
                <input type="hidden" name="action" value="check_out">
                <button type="submit">Check Out</button>
            </form>
        </div>

        <?php if ($todayRecord): ?>
            <p><strong>Presensi hari ini:</strong></p>
            <ul>
                <li>Check-in: <?php echo htmlspecialchars($todayRecord['check_in_time']); ?></li>
                <li>Check-out: <?php echo htmlspecialchars($todayRecord['check_out_time'] ?? '-'); ?></li>
            </ul>
        <?php else: ?>
            <p>Belum ada presensi hari ini.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Riwayat Presensi</h2>
        <?php if (empty($history)): ?>
            <p>Belum ada riwayat presensi.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Tanggal</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                </tr>
                <?php foreach ($history as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['attendance_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['check_in_time'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['check_out_time'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
