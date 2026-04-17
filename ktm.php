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

$user = $_SESSION['user'];

$nim = $fakultas = $jurusan = $angkatan = $telepon = $alamat = "";
$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = trim($_POST["nim"]);
    $fakultas = trim($_POST["fakultas"]);
    $jurusan = trim($_POST["jurusan"]);
    $angkatan = trim($_POST["angkatan"]);
    $telepon = trim($_POST["telepon"]);
    $alamat = trim($_POST["alamat"]);

    if (empty($nim)) {
        $errors["nim"] = "NIM wajib diisi";
    }
    if (empty($fakultas)) {
        $errors["fakultas"] = "Fakultas wajib diisi";
    }
    if (empty($jurusan)) {
        $errors["jurusan"] = "Jurusan wajib diisi";
    }
    if (empty($angkatan)) {
        $errors["angkatan"] = "Angkatan wajib diisi";
    }
    if (empty($telepon)) {
        $errors["telepon"] = "No. telepon wajib diisi";
    }
    if (empty($alamat)) {
        $errors["alamat"] = "Alamat wajib diisi";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO ktm_forms (user_id, nim, fakultas, jurusan, angkatan, alamat, telepon) VALUES (:user_id, :nim, :fakultas, :jurusan, :angkatan, :alamat, :telepon)");
        $stmt->execute([
            ':user_id' => $user['id'],
            ':nim' => $nim,
            ':fakultas' => $fakultas,
            ':jurusan' => $jurusan,
            ':angkatan' => $angkatan,
            ':alamat' => $alamat,
            ':telepon' => $telepon,
        ]);

        $success = "Form KTM berhasil disimpan. Silakan tunggu proses verifikasi.";
        $nim = $fakultas = $jurusan = $angkatan = $telepon = $alamat = "";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form KTM - Universitas Gua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f6f9ff;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0b5ed7;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .top-link {
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>
    <h1>Form KTM Universitas Gua</h1>
    <p>Isi data berikut untuk membuat identitas mahasiswa (KTM).</p>

    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($nim); ?>">
            <?php if (isset($errors['nim'])) echo '<div class="error">' . $errors['nim'] . '</div>'; ?>
        </div>

        <div class="form-group">
            <label for="fakultas">Fakultas:</label>
            <input type="text" id="fakultas" name="fakultas" value="<?php echo htmlspecialchars($fakultas); ?>">
            <?php if (isset($errors['fakultas'])) echo '<div class="error">' . $errors['fakultas'] . '</div>'; ?>
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan:</label>
            <input type="text" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($jurusan); ?>">
            <?php if (isset($errors['jurusan'])) echo '<div class="error">' . $errors['jurusan'] . '</div>'; ?>
        </div>

        <div class="form-group">
            <label for="angkatan">Angkatan:</label>
            <input type="text" id="angkatan" name="angkatan" value="<?php echo htmlspecialchars($angkatan); ?>">
            <?php if (isset($errors['angkatan'])) echo '<div class="error">' . $errors['angkatan'] . '</div>'; ?>
        </div>

        <div class="form-group">
            <label for="telepon">No. Telepon:</label>
            <input type="text" id="telepon" name="telepon" value="<?php echo htmlspecialchars($telepon); ?>">
            <?php if (isset($errors['telepon'])) echo '<div class="error">' . $errors['telepon'] . '</div>'; ?>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat:</label>
            <textarea id="alamat" name="alamat" rows="4"><?php echo htmlspecialchars($alamat); ?></textarea>
            <?php if (isset($errors['alamat'])) echo '<div class="error">' . $errors['alamat'] . '</div>'; ?>
        </div>

        <button type="submit">Kirim Permohonan KTM</button>
    </form>
</body>
</html>