<?php
// Initialize session or include session_start() if using sessions
// Example: session_start();

include("db_connection.php");

// Handle form submission to update status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    // Update status in the database
    $sql = "UPDATE leave_requests SET status = ? WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $request_id);

    if ($stmt->execute()) {
        // Success message or any further processing
        $status_message = "Status updated successfully.";
    } else {
        $status_message = "Error updating status: " . $conn->error;
    }

    $stmt->close();
}

// Fetch leave requests from database
$sql = "SELECT request_id, rollno, request_date, reason, status FROM leave_requests";
$result = $conn->query($sql);

$status_message = ""; // Initialize status message variable

?>

<table>

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
                echo "<button type='submit'>Update Successfully Status</button>";
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

<?php if (!empty($status_message)): ?>
    <p><?php echo $status_message; ?></p>
<?php endif; ?>
