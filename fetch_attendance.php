<?php
session_start();
if (!isset($_SESSION['rollno'])) {
    echo '<p class="text-danger">Please log in to view your attendance.</p>';
    exit();
}

$rollno = $_SESSION['rollno'];

$conn = new mysqli('localhost', 'username', 'password', 'attendance_system');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT attendance.date, attendance.status 
        FROM attendance 
        WHERE attendance.rollno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rollno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<table class="table table-striped table-bordered">';
    echo '<thead class="thead-dark"><tr><th>Date</th><th>Status</th></tr></thead><tbody>';
    while($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row['date'] . '</td><td>' . $row['status'] . '</td></tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p class="text-warning">No attendance records found.</p>';
}

$stmt->close();
$conn->close();
?>
