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

$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        if (!is_dir(__DIR__ . '/uploads')) {
            mkdir(__DIR__ . '/uploads', 0755, true);
        }

        $file = $_FILES['profile_photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Terjadi kesalahan saat upload foto.';
        } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedTypes, true)) {
            $errors[] = 'Hanya foto JPG, PNG, atau GIF yang boleh diupload.';
        } elseif ($file['size'] > $maxSize) {
            $errors[] = 'Ukuran foto maksimal 2MB.';
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;
            $targetPath = 'uploads/' . $filename;

            if (move_uploaded_file($file['tmp_name'], __DIR__ . '/' . $targetPath)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_photo = :photo WHERE id = :id");
                $stmt->execute([':photo' => $targetPath, ':id' => $userId]);

                $_SESSION['user']['profile_photo'] = $targetPath;
                $user['profile_photo'] = $targetPath;
                $success = 'Foto profil berhasil diupload.';
            } else {
                $errors[] = 'Gagal menyimpan foto profil.';
            }
        }
    } else {
        $errors[] = 'Silakan pilih file foto terlebih dahulu.';
    }
}

$photoUrl = isset($user['profile_photo']) && $user['profile_photo'] ? $user['profile_photo'] : 'https://via.placeholder.com/150?text=Foto+Profil';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mahasiswa - Universitas Gua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f9ff;
        }
        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .card h1 {
            margin-top: 0;
        }
        .field {
            margin-bottom: 12px;
        }
        .field strong {
            display: inline-block;
            width: 120px;
        }
        .top-link {
            display: block;
            margin-bottom: 20px;
            color: #0d6efd;
            text-decoration: none;
        }
        .profile-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #0d6efd;
            margin-bottom: 20px;
        }
        .upload-form {
            margin-top: 20px;
        }
        .upload-form input[type="file"] {
            display: block;
            margin-bottom: 10px;
        }
        .button-upload {
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 18px;
            cursor: pointer;
        }
        .button-upload:hover {
            background-color: #0b5ed7;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="top-link">&larr; Kembali ke Dashboard</a>

    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <div class="card">
        <h1>Profil Mahasiswa</h1>
        <img src="<?php echo htmlspecialchars($photoUrl); ?>" alt="Foto Profil" class="profile-photo">
        <p>Selamat datang, <?php echo htmlspecialchars($user['name']); ?>. Berikut data akun kamu.</p>

        <div class="field"><strong>Nama:</strong> <?php echo htmlspecialchars($user['name']); ?></div>
        <div class="field"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
        <div class="field"><strong>Terdaftar sejak:</strong> <?php echo htmlspecialchars($user['created_at']); ?></div>
        <div class="field"><strong>Peran:</strong> Mahasiswa</div>
    </div>

    <div class="card upload-form">
        <h2>Upload Foto Profil</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="file" name="profile_photo" accept="image/*">
            <button type="submit" class="button-upload">Upload Foto</button>
        </form>
    </div>
</body>
</html>
