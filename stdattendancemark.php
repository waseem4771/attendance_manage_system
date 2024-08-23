<?php
session_start(); // Start PHP session

// Ensure user is logged in and retrieve roll number from session
if (!isset($_SESSION['rollno'])) {
    header("Location: userLogin.php");
    exit();
}

$rollno = $_SESSION['rollno'];
$currentDate = date('Y-m-d');

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

// Check if attendance is marked for today
$sql_check = "SELECT * FROM attendance WHERE rollno = ? AND date = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $rollno, $currentDate);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Attendance is marked, proceed with deletion
    $sql_delete = "DELETE FROM attendance WHERE rollno = ? AND date = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ss", $rollno, $currentDate);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Attendance for today deleted successfully.');</script>";
    } else {
        echo "Error deleting attendance: " . $conn->error;
    }

    $stmt_delete->close();
} else {
    // Attendance is not marked for today, proceed with marking
    $sql_insert = "INSERT INTO attendance (rollno, date, status) VALUES (?, ?, 'Present')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ss", $rollno, $currentDate);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Attendance marked successfully.');</script>";
    } else {
        echo "Error marking attendance: " . $conn->error;
    }

    $stmt_insert->close(); // Close the statement after execution
}

$stmt_check->close();
$conn->close(); // Close database connection

// Redirect back to the main page or wherever appropriate
header("Location: UserPanel.php"); // Replace with your desired redirect location
exit();
?>
