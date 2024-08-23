<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

// Fetch filtered attendance records
if (isset($_POST['filterRollno'], $_POST['fromDate'], $_POST['toDate'])) {
    $rollno = $_POST['filterRollno'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];

    $sql = "SELECT * FROM attendance WHERE date BETWEEN ? AND ?";
    $params = [$fromDate, $toDate];

    if (!empty($rollno)) {
        $sql .= " AND rollno = ?";
        $params[] = $rollno;
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['rollno'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
<button style=\"background-color: #000; color: #fff;\" onclick=\"editAttendance(" . $row['id'] . ", '" . $row['status'] . "')\">Edit</button>
        <button style=\"background-color: #000; color: #fff;\" onclick=\"deleteAttendance(" . $row['id'] . ")\">Delete</button>
    
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No records found</td></tr>";
    }

    $stmt->close();
}

// Add attendance record
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $rollno = $_POST['rollno'];
    $attendanceDate = $_POST['attendanceDate'];
    $status = $_POST['status'];

    // Check if attendance already exists for the given rollno and date
    $checkSql = "SELECT COUNT(*) as count FROM attendance WHERE rollno = ? AND date = ?";
    $checkStmt = $conn->prepare($checkSql);
    if (!$checkStmt) {
        die("Preparation failed: " . $conn->error);
    }
    
    $checkStmt->bind_param('ss', $rollno, $attendanceDate);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $row = $checkResult->fetch_assoc();
    $count = $row['count'];
    
    $checkStmt->close();
    
    if ($count > 0) {
        echo "Attendance already marked for roll number $rollno on $attendanceDate.";
    } else {
        // Proceed with inserting attendance record
        $sql = "INSERT INTO attendance (rollno, date, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param('sss', $rollno, $attendanceDate, $status);
        if ($stmt->execute()) {
            echo "Attendance added successfully.";
        } else {
            echo "Error adding attendance: " . $stmt->error;
        }

        $stmt->close();
    }
}


// Edit attendance status
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $newStatus = $_POST['status'];

    $sql = "UPDATE attendance SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param('si', $newStatus, $id);
    if ($stmt->execute()) {
        echo "Attendance updated successfully.";
    } else {
        echo "Error updating attendance: " . $stmt->error;
    }

    $stmt->close();
}

// Delete attendance record
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];

    $sql = "DELETE FROM attendance WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo "Attendance deleted successfully.";
    } else {
        echo "Error deleting attendance: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
