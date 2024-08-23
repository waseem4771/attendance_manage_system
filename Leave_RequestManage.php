<?php
// Initialize session or include session_start() if using sessions
// Example: session_start();

include("db_connection.php"); // Ensure this includes your database connection

// Handle form submission to update status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    // Update status in the database
    $sql = "UPDATE leave_requests SET status = ? WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $request_id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        $status_message = "Error updating status: " . $conn->error;
    }

    $stmt->close();
}

// Fetch leave requests from database
$sql = "SELECT request_id, rollno, request_date, reason, status FROM leave_requests";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Leave Requests</title>
    <!-- Include your CSS stylesheets here -->
    <style>
        /* Add your CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        form {
            display: inline-block;
        }
        select, button {
            padding: 6px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center; font-size: 30px; font-weight: bold;">Leave Requests</h2>

        <!-- Leave requests table -->
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Roll No</th>
                    <th>Request Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["request_id"] . "</td>";
                        echo "<td>" . $row["rollno"] . "</td>";
                        echo "<td>" . $row["request_date"] . "</td>";
                        echo "<td>" . $row["reason"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>";
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                        echo "<input type='hidden' name='request_id' value='" . $row["request_id"] . "'>";
                        echo "<select name='status'>";
                        echo "<option value='pending' " . ($row["status"] == "pending" ? "selected" : "") . ">Pending</option>";
                        echo "<option value='approved' " . ($row["status"] == "approved" ? "selected" : "") . ">Approved</option>";
                        echo "<option value='rejected' " . ($row["status"] == "rejected" ? "selected" : "") . ">Rejected</option>";
                        echo "</select>";
                        echo "<button type='submit'>Update Status</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No leave requests found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <?php if (isset($status_message)): ?>
            <p><?php echo $status_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
