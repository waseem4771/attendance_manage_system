<?php
session_start();

if (!isset($_SESSION['rollno'])) {
    header("Location: userLogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $rollno = $_SESSION['rollno'];
    $requestDate = date('Y-m-d');
    $reason = htmlspecialchars($_POST['message']); // Sanitize input for security

    // Database connection
    $servername = "localhost";
    $username = "root"; // Replace with your database username
    $password = ""; // Replace with your database password
    $dbname = "attendanceSystem";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $sql = "INSERT INTO leave_requests (rollno, request_date, reason, status)
            VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $rollno, $requestDate, $reason);

    // Execute statement
    if ($stmt->execute()) {
        echo "<script>alert('Leave request submitted successfully.');</script>";
        header("Location: UserPanel.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>
