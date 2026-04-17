<?php
session_start();

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

// Initialize variables
$name = $email = $password = $confirm_password = "";
$errors = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = trim($_POST["name"]);
    if (empty($name)) {
        $errors["name"] = "Nama wajib diisi";
    }

    $email = trim($_POST["email"]);
    if (empty($email)) {
        $errors["email"] = "Email wajib diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Format email tidak valid";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $errors["email"] = "Email sudah terdaftar";
        }
    }

    $password = $_POST["password"];
    if (empty($password)) {
        $errors["password"] = "Password wajib diisi";
    } elseif (strlen($password) < 6) {
        $errors["password"] = "Password minimal 6 karakter";
    }

    $confirm_password = $_POST["confirm_password"];
    if (empty($confirm_password)) {
        $errors["confirm_password"] = "Konfirmasi password wajib diisi";
    } elseif ($password !== $confirm_password) {
        $errors["confirm_password"] = "Password tidak cocok";
    }

    // If no errors, register the user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashed_password,
        ]);

        // Redirect to login
        header("Location: login.php?registered=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - Universitas Gua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f3f7fb;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        .success {
            color: green;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Daftar Mahasiswa Baru</h1>
    <p>Selamat datang di Universitas Gua. Buat akun untuk masuk ke sistem kampus.</p>

    <?php if (isset($_GET['registered'])): ?>
        <div class="success">Akun berhasil dibuat! Silakan login.</div>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="name">Nama Lengkap:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <?php if (isset($errors["name"])) echo "<div class='error'>{$errors["name"]}</div>"; ?>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (isset($errors["email"])) echo "<div class='error'>{$errors["email"]}</div>"; ?>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <?php if (isset($errors["password"])) echo "<div class='error'>{$errors["password"]}</div>"; ?>
        </div>

        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <?php if (isset($errors["confirm_password"])) echo "<div class='error'>{$errors["confirm_password"]}</div>"; ?>
        </div>

        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>