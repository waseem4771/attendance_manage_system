<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"> <!-- Added maximum-scale=1.0 -->
    <link rel="stylesheet" type="text/css" href="/xampp/htdocs/Admin_Panel.css">
    <script src="/htdocs/Admin_Panel.js"></script>

    <title>RegistrationForm</title>
    
</head>

<style>
    /* Global Styles */
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
}

.heading {
    text-align: center;
    color: black;
}

.form-group {
    margin-bottom: 20px;
}
select {
    width: 106.5%;
    padding: 10px;
    border: 1px solid #dddfe2;
    border-radius: 5px;
    background-color: #f0f2f5;
    font-size: 14px;
    color: #1c1e21;
    outline: none;
}
p{
    text-align: center;
}
button:hover{
    font-weight: bold;
        }
button{
            width: 100%;
            padding: 10px;
            background-color:white;
            font-size: 14px;
            outline: none;
            color: blue;
            cursor: pointer;
            border-color: white;
            transition: var(--transition-timing);

        }


input[type="text"],
input[type="email"],
input[type="password"],
input[type="submit"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #dddfe2;
    border-radius: 5px;
    background-color: #f0f2f5;
    font-size: 14px;
    color: #1c1e21;
    outline: none;
}

input[type="submit"] {
    background-color: black;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
}

input[type="submit"]:hover {
    background-color: #FF91A4;
}

</style>
<div class="wrap">
    <h1 class="heading">Sign Up</h1>
    <form method="post">
        
        <div class="form-group">
            <input type="text" name="Name" id="Name" required placeholder="Enter your name">
        </div>

        <div class="form-group">
            <input type="email" id="email" name="email" required placeholder="Enter your email"><br><br>
        </div>

        <div class="form-group">
            <input type="password" id="password" name="password" required placeholder="Enter the Password"><br><br>
        </div>
        


        <input type="submit" name="submit" value="Register">
        <p>or</p>
        <button type="button" onclick="window.location.href='/AdminLogin.php'">Already have an Account</button>
    </form>
</div>


<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (!empty($_POST['Name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role'])) {
        // Sanitize input data to prevent SQL injection
        $username = htmlspecialchars($_POST['Name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $role = htmlspecialchars($_POST['role']);

        // Validate email format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Connect to your database (replace database credentials with your own)
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "attendanceSystem";

            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Determine which table to insert the data into based on the role
            if ($role === "admin") {
                $sql = "INSERT INTO admin (Name, email, password) VALUES ('$username', '$email', '$password')";
            } else {
                $sql = "INSERT INTO users (Name, email, password) VALUES ('$username', '$email', '$password')";
            }

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Successfully Registered!');</script>";
                echo "<script>clearForm();</script>";
               
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            echo "Invalid email format";
            echo "<script>clearForm();</script>";
        }
    } else {
        echo "All fields are required";
    }
}
?>

</body>
</html>