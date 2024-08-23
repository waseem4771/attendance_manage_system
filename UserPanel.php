<?php
session_start(); // Start PHP session

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['rollno'])) {
    header("Location: userLogin.php");
    exit();
}

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

$rollno = $_SESSION['rollno'];

// Fetch student information
$sql_student = "SELECT * FROM students WHERE rollno = ?";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->bind_param("s", $rollno);
$stmt_student->execute();
$result_student = $stmt_student->get_result();

// Fetch attendance records
$sql_attendance = "SELECT date, status FROM attendance WHERE rollno = ?";
$stmt_attendance = $conn->prepare($sql_attendance);
$stmt_attendance->bind_param("s", $rollno);
$stmt_attendance->execute();
$result_attendance = $stmt_attendance->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UserPanel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/UserPanel.css">
</head>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile" onclick="changeProfile()">
        <img id="profile-img" src="" alt="Profile Picture">
        <div class="change-pic">Change Picture</div>
    </div>
    <ul>
        <!-- Navigation links -->
        <li><a href="#" onclick="showStudentInfo()"><i class="bi bi-person"></i> Dashboard</a></li>
        <li><a href="#" onclick="showAttendance()"><i class="bi bi-clipboard-check"></i> View Attendance</a></li>
        <li><a href="#" onclick="showMarkAttendanceForm()"><i class="bi bi-calendar-check"></i> Mark Attendance</a></li>

        <li><a href="#" onclick="showLeave()"><i class="bi bi-journal-text"></i> Leave Requests</a></li>

    </ul>
</div>
<!-- End Sidebar -->

<!-- Main Content -->
<div class="content">
    <div class="profilebtn">
        <!-- Profile buttons -->

        <a href="Userlogout.php" class="logout-btn">Log out</a>
    </div>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <!-- Breadcrumbs content here if needed -->
        </div>
    </section>
    <!-- End Breadcrumbs -->

    <!-- Student Information -->
    <div id="studentInfo">
        <h2><b>Student Information</b></h2>
        <div class="stdprofile">
            <!-- Display student profile details dynamically -->
            <?php if ($result_student->num_rows > 0) : ?>
                <table class="table table-bordered">
                    <tbody>
                        <?php while ($row = $result_student->fetch_assoc()) : ?>
                            <tr>
                                <td colspan="2" style="text-align: center; font-weight: bold; font-size:25px;"><?php echo $row['name']; ?></td>

                            </tr>

                            <tr>
                                <th>Roll Number</th>
                                <td><?php echo $row['rollno']; ?></td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td><?php echo $row['department']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $row['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Phone#</th>
                                <td><?php echo $row['phoneno']; ?></td>
                            </tr>
                            <tr>
                                <th>HomeAddress</th>
                                <td><?php echo $row['homeaddress']; ?></td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td><?php echo $row['age']; ?></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td><?php echo $row['gender']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="text-warning">No student information found.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- End Student Information -->

    <!-- Attendance Table -->
    <div id="attendanceTable" class="attendance-table">
        <h2>Attendance Records</h2>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Display attendance records dynamically -->
                <?php while ($row = $result_attendance->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- End Attendance Table -->


    <!-- Mark Attendance Form -->
    <div id="markAttendanceForm" class="mark-attendance">
        <h2>Mark Attendance</h2>
        <form action="stdattendancemark.php" method="POST">
            <!-- Hidden input field to store the current date -->
            <input type="hidden" id="attendanceDate" name="attendanceDate">

            <label for="attendanceStatus">Status:</label>
            <select id="attendanceStatus" name="attendanceStatus" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Late">Late</option>
            </select>

            <button type="submit">Submit Attendance</button>
        </form>

        <form action="stdattendancemark.php" method="POST">
            <button type="submit" name="deleteAttendance">Delete Today's Attendance</button>
        </form>
    </div>

    <!-- End Mark Attendance Form -->

    <!-- Leave_Request Form -->

    <div id="leaveRequestForm" class="leave-request">
        <h2>Leave Request Form</h2>
        <form action="LeaveRequest.php" method="POST">
            <label for="message">Leave Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Submit Leave Request</button>
        </form>
    </div>


    <!-- Leave_Request Form End -->


</div>
<!-- End Main Content -->

<!-- Profile Picture Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Select Profile Picture</h2>
        <input type="file" accept="image/*" id="upload-profile-pic" style="display: none;" onchange="previewProfile(this)">
        <button onclick="document.getElementById('upload-profile-pic').click()">Upload Image</button>
        <button onclick="setDefaultProfile()">Use Default</button>
    </div>
</div>

<!-- End Main Content -->

<!-- JavaScript to toggle views -->
<script>
    function showStudentInfo() {
        document.getElementById('studentInfo').style.display = 'block';
        document.getElementById('attendanceTable').style.display = 'none';
        document.getElementById('markAttendanceForm').style.display = 'none';
        document.getElementById('leaveRequestForm').style.display = 'none';
    }

    function showAttendance() {
        document.getElementById('studentInfo').style.display = 'none';
        document.getElementById('markAttendanceForm').style.display = 'none';
        document.getElementById('attendanceTable').style.display = 'block';
        document.getElementById('leaveRequestForm').style.display = 'none';
    }

    function showLeave() {
        document.getElementById('studentInfo').style.display = 'none';
        document.getElementById('markAttendanceForm').style.display = 'none';
        document.getElementById('attendanceTable').style.display = 'none';
        document.getElementById('leaveRequestForm').style.display = 'block';
    }

    function showMarkAttendanceForm() {
        document.getElementById('studentInfo').style.display = 'none';
        document.getElementById('attendanceTable').style.display = 'none';
        document.getElementById('markAttendanceForm').style.display = 'block';
        document.getElementById('leaveRequestForm').style.display = 'none';

        // Set the current date in the attendanceDate input field
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('attendanceDate').value = today;
    }
</script>



<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Select Profile Picture</h2>
        <input type="file" accept="image/*" id="upload-profile-pic" style="display: none;" onchange="previewProfile(this)">
        <button onclick="document.getElementById('upload-profile-pic').click()">Upload Image</button>
        <button onclick="setDefaultProfile()">Use Default</button>
    </div>
</div>

<script>
    // Check if there is a stored profile picture in localStorage
    var storedProfilePic = localStorage.getItem("profilePic");

    // If there is a stored profile picture, set it as the profile image source
    if (storedProfilePic) {
        document.getElementById("profile-img").src = storedProfilePic;
    }

    function changeProfile() {
        var modal = document.getElementById("profileModal");
        modal.style.display = "block";
    }

    function closeModal() {
        var modal = document.getElementById("profileModal");
        modal.style.display = "none";
    }

    function setDefaultProfile() {
        var defaultProfilePic = "assets/img/default-profile-img.jpg"; // Path to default image
        document.getElementById("profile-img").src = defaultProfilePic;
        localStorage.setItem("profilePic", defaultProfilePic); // Store default image path in localStorage
        closeModal();
    }

    function previewProfile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var profileImg = document.getElementById("profile-img");
                profileImg.src = e.target.result;
                localStorage.setItem("profilePic", e.target.result); // Store selected image data URL in localStorage
                closeModal();
            }

            reader.readAsDataURL(input.files[0]); // Read the uploaded file as a data URL

            // Reset the file input to allow selecting the same file again
            input.value = '';
        }
    }
</script>
</body>

</html>