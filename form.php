<?php
// Initialize variables
$name = $email = $password = $message = $gender = $country = "";
$interests = [];
$errors = [];

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = trim($_POST["name"]);
    if (empty($name)) {
        $errors["name"] = "Name is required";
    }

    $email = trim($_POST["email"]);
    if (empty($email)) {
        $errors["email"] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }

    $password = $_POST["password"];
    if (empty($password)) {
        $errors["password"] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors["password"] = "Password must be at least 6 characters";
    }

    $message = trim($_POST["message"]);

    if (isset($_POST["gender"])) {
        $gender = $_POST["gender"];
    }

    if (isset($_POST["country"])) {
        $country = $_POST["country"];
    }

    if (isset($_POST["interests"])) {
        $interests = $_POST["interests"];
    }

    // If no errors, process the form
    if (empty($errors)) {
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO form_data
            (name, email, password, gender, country, interests, message)
            VALUES
            (:name, :email, :password, :gender, :country, :interests, :message)
        ");

        $stmt->execute([
            ':name'      => $name,
            ':email'     => $email,
            ':password'  => password_hash($password, PASSWORD_DEFAULT),
            ':gender'    => $gender,
            ':country'   => $country,
            ':interests' => implode(', ', $interests),
            ':message'   => $message,
        ]);

        // Show success message
        echo "<div class='result'>";
        echo "<h2>Form Submitted Successfully!</h2>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Gender:</strong> " . htmlspecialchars($gender) . "</p>";
        echo "<p><strong>Country:</strong> " . htmlspecialchars($country) . "</p>";
        echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars($message)) . "</p>";
        if (!empty($interests)) {
            echo "<p><strong>Interests:</strong> " . implode(", ", array_map('htmlspecialchars', $interests)) . "</p>";
        }
        echo "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"], textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="radio"], input[type="checkbox"] {
            margin-right: 5px;
        }
        .radio-group, .checkbox-group {
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>PHP FORM EXAMPLE</h1>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <?php if (isset($errors["name"])) echo "<div class='error'>{$errors["name"]}</div>"; ?>
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (isset($errors["email"])) echo "<div class='error'>{$errors["email"]}</div>"; ?>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <?php if (isset($errors["password"])) echo "<div class='error'>{$errors["password"]}</div>"; ?>
        </div>

        <div class="form-group">
            <label>Gender:</label>
            <div class="radio-group">
                <input type="radio" id="male" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>>
                <label for="male">Male</label>
            </div>
            <div class="radio-group">
                <input type="radio" id="female" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>>
                <label for="female">Female</label>
            </div>
            <div class="radio-group">
                <input type="radio" id="other" name="gender" value="Other" <?php if ($gender == "Other") echo "checked"; ?>>
                <label for="other">Other</label>
            </div>
        </div>

        <div class="form-group">
            <label for="country">Country:</label>
            <select id="country" name="country">
                <option value="">Select a country</option>
                <option value="USA" <?php if ($country == "USA") echo "selected"; ?>>United States</option>
                <option value="Canada" <?php if ($country == "Canada") echo "selected"; ?>>Canada</option>
                <option value="UK" <?php if ($country == "UK") echo "selected"; ?>>United Kingdom</option>
                <option value="Australia" <?php if ($country == "Australia") echo "selected"; ?>>Australia</option>
                <option value="Germany" <?php if ($country == "Germany") echo "selected"; ?>>Germany</option>
                <option value="France" <?php if ($country == "France") echo "selected"; ?>>France</option>
                <option value="Japan" <?php if ($country == "Japan") echo "selected"; ?>>Japan</option>
                <option value="Indonesia" <?php if ($country == "Indonesia") echo "selected"; ?>>Indonesia</option>
                <option value="Other" <?php if ($country == "Other") echo "selected"; ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Interests:</label>
            <div class="checkbox-group">
                <input type="checkbox" id="programming" name="interests[]" value="Programming" <?php if (in_array("Programming", $interests)) echo "checked"; ?>>
                <label for="programming">Programming</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="design" name="interests[]" value="Design" <?php if (in_array("Design", $interests)) echo "checked"; ?>>
                <label for="design">Design</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="gaming" name="interests[]" value="Gaming" <?php if (in_array("Gaming", $interests)) echo "checked"; ?>>
                <label for="gaming">Gaming</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="sports" name="interests[]" value="Sports" <?php if (in_array("Sports", $interests)) echo "checked"; ?>>
                <label for="sports">Sports</label>
            </div>
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5"><?php echo htmlspecialchars($message); ?></textarea>
        </div>

        <button type="submit">Submit Form</button>
    </form>
</body>
</html>