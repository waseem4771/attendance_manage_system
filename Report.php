<?php
// Start session for storing report data (if needed)
// Include database connection
include("db_connection.php");

// Function to generate individual user attendance report for a specific roll number and date range
function generateIndividualReport($rollno, $fromDate, $toDate, $conn) {
    // Query to get attendance records for the specific roll number within the date range using prepared statements
    $sql = "SELECT * FROM attendance WHERE rollno = ? AND date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $rollno, $fromDate, $toDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize counters for present, absent, and late
    $presentCount = 0;
    $absentCount = 0;
    $lateCount = 0;

    // Iterate through attendance records
    while ($row = $result->fetch_assoc()) {
        $status = $row['status'];
        // Increment counters based on status
        switch ($status) {
            case 'Present':
                $presentCount++;
                break;
            case 'Absent':
                $absentCount++;
                break;
            case 'Late':
                $lateCount++;
                break;
            default:
                // Handle any other status
                break;
        }
    }

    // Close statement
    $stmt->close();

    // Calculate total days (you might want to adjust this dynamically)
    $totalDays = 30; // Assuming a month with 30 days

    // Calculate grade based on attendance
    $grade = calculateGrade($presentCount, $totalDays);

    // Build the output HTML for the report row
    $output = "<tr>";
    $output .= "<td>$rollno</td>";
    $output .= "<td>$presentCount</td>";
    $output .= "<td>$absentCount</td>";
    $output .= "<td>$lateCount</td>";
    $output .= "<td>$grade</td>";
    $output .= "</tr>";

    return $output;
}

// Function to calculate grade based on attendance percentage
function calculateGrade($presentCount, $totalDays) {
    if ($totalDays > 0) {
        $attendancePercentage = ($presentCount / $totalDays) * 100;
        if ($attendancePercentage >= 90) {
            return "A";
        } elseif ($attendancePercentage >= 80) {
            return "B";
        } elseif ($attendancePercentage >= 70) {
            return "C";
        } elseif ($attendancePercentage >= 60) {
            return "D";
        } else {
            return "F";
        }
    } else {
        return "N/A"; // No attendance recorded
    }
}

// Initialize variables to hold form data
$reportOutput = "";
$fromDate = "";
$toDate = "";
$rollno = "";

// Handle form submission to generate reports
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['from_date']) && isset($_POST['to_date'])) {
    // Validate and sanitize input
    $fromDate = $_POST['from_date'];
    $toDate = $_POST['to_date'];
    $rollno = isset($_POST['rollno']) ? $_POST['rollno'] : '';

    // Query to get list of distinct roll numbers for students with attendance records
    $sqlStudents = "SELECT DISTINCT rollno FROM attendance";

    // Append WHERE clause if roll number is provided (use prepared statement for security)
    if (!empty($rollno)) {
        $sqlStudents = "SELECT DISTINCT rollno FROM attendance WHERE rollno = ?";
        $stmtStudents = $conn->prepare($sqlStudents);
        $stmtStudents->bind_param("s", $rollno);
        $stmtStudents->execute();
        $resultStudents = $stmtStudents->get_result();
    } else {
        $resultStudents = $conn->query($sqlStudents);
    }

    $reportOutput = "<h2>Attendance Report from $fromDate to $toDate</h2>";
    $reportOutput .= "<table border='1'>";
    $reportOutput .= "<tr><th>Roll No</th><th>Presents</th><th>Absents</th><th>Late</th><th>Grade</th></tr>";

    // Generate individual reports for each student
    while ($row = $resultStudents->fetch_assoc()) {
        $rollno = $row['rollno'];
        $reportOutput .= generateIndividualReport($rollno, $fromDate, $toDate, $conn);
    }

    $reportOutput .= "</table>";

    // Close statement and connection
    if (!empty($stmtStudents)) {
        $stmtStudents->close();
    }
    $conn->close();
}
?>