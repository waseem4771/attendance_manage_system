<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .wrap {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 320px;
            margin: 80px auto;
            padding: 20px;
            text-align: center;
        }

        .heading {
            text-align: center;
            color: black;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"],
        input[type="button"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 5px;
            background-color: #f0f2f5;
            font-size: 14px;
            color: #1c1e21;
            outline: none;
        }

        button:hover {
            font-weight: bold;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: white;
            font-size: 14px;
            outline: none;
            color: blue;
            cursor: pointer;
            border-color: white;
            transition: var(--transition-timing);

        }

        input[type="submit"],
        input[type="button"] {
            background-color: black;
            color: white;
            text-transform: uppercase;
            cursor: pointer;
            font-weight: bold;
            transition: var(--transition-timing);
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #FF91A4;
        }

        p {
            text-align: center;
        }

        button {
            position: relative;
        }

        .error-message {
            color: #dc143c;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        .password-strength {
            font-size: 12px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <h1 class="heading">Login</h1>
    <form id="login-form" method="post">
        <div class="form-group">
            <input type="text" name="rollno" id="rollno" required placeholder="Roll number">
        </div>
        <div class="form-group">
            <input type="password" name="password" id="password" required placeholder="Password" onkeyup="checkPasswordStrength()">
        </div>

        <div id="password-strength" class="password-strength"></div>
        <input type="submit" name="submit" value="Log In">
        <p>or</p>
        <button type="button" onclick="window.location.href='/StudentRegisteration.php'">Register</button>

        <?php
session_start(); // Start PHP session

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0"); // Start PHP session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root"; // Change this to your database username
    $password = ""; // Change this to your database password
    $dbname = "attendanceSystem";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input data to prevent SQL injection
    $rollno = mysqli_real_escape_string($conn, $_POST['rollno']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check user credentials
    $sql = "SELECT * FROM students WHERE rollno = '$rollno' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User authenticated, set session variables
        $_SESSION['rollno'] = $rollno; // Set session variable for roll number
        $_SESSION['loggedin'] = true; // Set session variable for logged-in status
        header("Location: UserPanel.php"); // Redirect to user panel or any desired page after login
        exit();
    } else {
        // Invalid credentials
        $error_message = "Invalid roll number or password";
    }

    $conn->close(); // Close database connection
}
?>

    </form>
</div>
<script>
    function clearFields() {
        document.getElementById("rollno").value = ""; // Clear username field
        document.getElementById("password").value = ""; // Clear password field
    }

    function checkPasswordStrength() {
        var password = document.getElementById("password").value;
        var strengthBadge = document.getElementById("password-strength");

        var strength = 0;
        if (password.match(/[a-z]+/)) {
            strength += 1;
        }
        if (password.match(/[A-Z]+/)) {
            strength += 1;
        }
        if (password.match(/[0-9]+/)) {
            strength += 1;
        }
        if (password.match(/[!@#$%^&*()]+/)) {
            strength += 1;
        }

        if (password.length >= 8 && password.length <= 16) {
            if (password.length <= 9) {
                strengthBadge.textContent = "Weak";
            } else if (password.length <= 12) {
                strengthBadge.textContent = "Medium";
            } else {
                strengthBadge.textContent = "Strong";
            }
        } else {
            strengthBadge.textContent = "";
        }
    }

    // Check if the login attempt was unsuccessful (error message shown)
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])): ?>
        <?php if (!empty($_POST['rollno']) && empty($_POST['password'])): ?>
            document.getElementById("password").value = ""; // Clear password field only
        <?php endif; ?>
    <?php endif; ?>
</script>
</body>
</html>
