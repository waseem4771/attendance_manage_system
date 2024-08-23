<?php
session_start(); // Start PHP session

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['Name'])) {
    header("Location: AdminLogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AdminPanel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/AdminPanel.css">


  <style>
  


 /* Add Attendance Button Style */
 #showAddAttendanceButton {
  background-color: #000; /* Black background */
  color: #fff; /* White text color */
  border: none; /* Remove border */
  padding: 10px 20px; /* Padding inside the button */
  text-align: center; /* Center text */
  text-decoration: none; /* Remove underline from text */
  display: inline-block; /* Inline-block for margin */
  font-size: 16px; /* Font size */
  margin-top: 20px; /* Space above the button */
  cursor: pointer; /* Pointer cursor on hover */
  border-radius: 4px; /* Rounded corners */
  transition: background-color 0.3s; /* Smooth transition for background color */
}

#showAddAttendanceButton:hover {
             background-color: #FF91A4;
  /* Darker black on hover */
}

/* Add Attendance Section */
#addAttendanceSection {
  display: none; /* Initially hidden */
  margin-top: 20px; /* Space above the section */
}

#addAttendanceSection .container {
  background-color: #f8f9fa; /* Light gray background */
  padding: 20px; /* Padding inside the container */
  border-radius: 5px; /* Rounded corners for the container */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
}

#addAttendanceSection h2 {
  margin-bottom: 20px; /* Space below the heading */
  color: black; /* Dark text color for heading */
}

#addAttendanceForm {
  display: flex;
  flex-direction: column;
}

#addAttendanceForm label {
  margin-bottom: 5px; /* Space below the labels */
  color: black; /* Dark text color for labels */
}

#addAttendanceForm input,
#addAttendanceForm select {
  padding: 10px; /* Padding inside inputs and select */
  margin-bottom: 15px; /* Space below inputs and select */
  border: 1px solid #dee2e6; /* Light border */
  border-radius: 4px; /* Rounded corners for inputs and select */
  font-size: 16px; /* Font size */
}

#addAttendanceForm button {
  background-color: #000; /* Black background */
  color: #fff; /* White text color */
  border: none; /* Remove border */
  padding: 10px 20px; /* Padding inside the button */
  text-align: center; /* Center text */
  text-decoration: none; /* Remove underline from text */
  display: inline-block; /* Inline-block for margin */
  font-size: 16px; /* Font size */
  cursor: pointer; /* Pointer cursor on hover */
  border-radius: 4px; /* Rounded corners */
  transition: background-color 0.3s; /* Smooth transition for background color */
}

#addAttendanceForm button:hover {
            background-color: #FF91A4;
              /* Darker black on hover */
}



        /* Table styles */
        table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
      }

      table th, table td {
          border: 1px solid #ddd;
          padding: 12px;
          text-align: left;
      }

      table th {
          background-color: black;
          color: white;
      }

      table tr:nth-child(even) {
          background-color: #f2f2f2;
      }

      table tr:hover {
          background-color: #eaeaea;
      }

      /* Form styles */
      form {
          display: inline-block;
      }

      select, button {
          padding: 8px;
          font-size: 14px;
          margin-right: 5px;
          border-radius: 5px;
          border: 1px solid #ccc;
      }

      button {
          background-color: black;
          color: #fff;
          border: none;
          cursor: pointer;
      }

      button:hover {
          background-color: #FF91A4;
      }

      /* Message styles */
      .status-message {
          margin-top: 20px;
          padding: 10px;
          border-radius: 5px;
          text-align: center;
      }

      .status-message.success {
          background-color: #d4edda;
          color: #155724;
      }

      .status-message.error {
          background-color: #f8d7da;
          color: #721c24;
      }

      .content {

margin-left: 250px; /* Adjust content margin to fit sidebar */
padding: 20px; /* Padding inside content area */
width: 100%; /* Take remaining width */
}
.logout-btn {
  font-weight: bold;
    color: blue; /* Example text color */

    border-radius: 0.5px;
}
.profilebtn {
  
  
position: absolute;
top: 20px;
right: 20px;
}
  </style>
</head>

<body>

  <!-- ======= Sidebar ======= -->
  <div class="sidebar">
    <div class="profile" onclick="changeProfile()">
      <img id="profile-img" src="" alt="Profile Picture">
      <div class="change-pic">Change Picture</div>
    </div>

    <ul>

      <li><a href="#" onclick="showViewStudents()"><i class="bi bi-people"></i> View Students</a></li>
      <li><a href="#" onclick="showAttendanceReport()"><i class="bi bi-file-text"></i> Attendance Report</a></li>

      <li><a href="#" onclick="showManageAttendance()"><i class="bi bi-calendar-check"></i> Manage Attendance</a></li>
      <li><a href="#" onclick="showViewRequests()"><i class="bi bi-journal-text"></i> View Requests</a></li>

     
    </ul>
  </div>
  <!-- End Sidebar -->

  <!-- ======= Main Content ======= -->
  <div class="content">

  <div class="profilebtn">
        <!-- Profile buttons -->

        <a href="AdminLogout.php" class="logout-btn">Log out</a>
    </div>
    <!-- ======= Breadcrumbs ======= -->
    <section class="breadcrumbs">
      <div class="container">
      
      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= View Students Section ======= -->
    <section id="viewStudentsContent" class="content-section">
      <h2 style="text-align: center; font-size: 30px; font-weight: bold;">Student Records</h2>


      <!-- Student records table -->

      <br>
      <form id="searchStudentsForm" class="search-form" method="GET" action="">
        <label for="searchInput">Search Student:</label>
        <input type="text" id="searchInput" name="searchInput">
        <button type="submit" style="background-color: #000; color: #fff; transition: background-color 0.3s;">Search</button>

      </form>
      <?php
      // Database connection
      include("db_connection.php");

      // Fetching filter parameter
      $searchInput = isset($_GET['searchInput']) ? $_GET['searchInput'] : '';

      // SQL query to fetch student records
      $sql = "SELECT rollno, name, email, phoneno, age, gender, homeaddress FROM students";

      // If search input is provided, filter by name or rollno
      if (!empty($searchInput)) {
        $sql .= " WHERE name LIKE ? OR rollno = ?";
        $searchInputWildcard = "%$searchInput%"; // Add wildcards for partial matching
      }

      // Prepare SQL statement
      $stmt = $conn->prepare($sql);
      if (!$stmt) {
        die("Preparation failed: " . $conn->error);
      }

      // Bind parameters if search input is provided
      if (!empty($searchInput)) {
        $stmt->bind_param('ss', $searchInputWildcard, $searchInput);
      }

      $stmt->execute();
      $result = $stmt->get_result();

      // HTML structure for the table
      echo '<table id="studentsTable">
        <thead>
          <tr>
            <th>Roll No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone No</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Home Address</th>
          </tr>
        </thead>
        <tbody>';

      // Check if there are records found
      if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["rollno"] . "</td>";
          echo "<td>" . $row["name"] . "</td>";
          echo "<td>" . $row["email"] . "</td>";
          echo "<td>" . $row["phoneno"] . "</td>";
          echo "<td>" . $row["age"] . "</td>";
          echo "<td>" . $row["gender"] . "</td>";
          echo "<td>" . $row["homeaddress"] . "</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No students found</td></tr>";
      }

      echo '</tbody></table>';

      $stmt->close();
      $conn->close();
      ?>

    </section>
    <!-- End View Students Section -->



<!-- Add Attendance Section (Hidden by default) -->
<section id="addAttendanceSection" class="content-section" style="display: none;">
    <div class="container">
        <h2>Add Attendance</h2>
        <form id="addAttendanceForm">
            <label for="rollno">Roll No:</label>
            <input type="text" id="rollno" name="rollno" required>
            <label for="attendanceDate">Date:</label>
            <input type="date" id="attendanceDate" name="attendanceDate" required>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Late">Late</option>
            </select>
            <button type="submit">Add Attendance</button>
        </form>
    </div>
</section>
    <!-- ======= Manage Attendance Section ======= -->
    <section id="manageAttendanceContent" class="content-section" style="display: none;">
      <div class="container">
      <button id="showAddAttendanceButton" onclick="showAddAttendanceForm()">Add Attendance</button>
        <h2>Manage Attendance</h2>
        <!-- Include your manage attendance form and table here -->
       
        <form id="filterAttendanceForm" onsubmit="return fetchFilteredAttendance();">
          <label for="filterRollno">Roll No:</label>
          <input type="text" id="filterRollno" name="filterRollno">
          <label for="fromDate">From Date:</label>
          <input type="date" id="fromDate" name="fromDate" required>
          <label for="toDate">To Date:</label>
          <input type="date" id="toDate" name="toDate" required>
          <button type="submit" style="background-color: #000; color: #fff; transition: background-color 0.3s;">Filter</button>

        </form>
        <table id="attendanceTable">
          <thead>
            <tr>
              <th>Roll No</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Attendance records will be loaded here via PHP -->
          </tbody>
        </table>
      </div>

      <!-- Search form -->

    </section>
    <!-- End Manage Attendance Section -->




<!-- ======= View Requests Section ======= -->
<section id="viewRequestsContent" class="content-section" style="display: none;">
  <div class="container">
    <h2>Leave Requests</h2>

    <!-- Table to display leave requests -->

    <table id="leaveRequestsTable" class="table table-striped">
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
    <tbody>
    <?php include("Leaverequests.php"); ?>
    </tbody>
</table>


</section>
<!-- End View Requests Section -->


    <!-- Attendance Report Section -->
     <?php include("Report.php");?>
    <section id="attendanceReportContent" class="content-section" style="display: none;">
      <div class="container">
        <h2>Attendance Report</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Roll No:</label>
    <input type="text" name="rollno" >
    <br><br>
    <label>From Date:</label>
    <input type="date" name="from_date" value="<?php echo htmlspecialchars($fromDate); ?>" required>
    <label>To Date:</label>
    <input type="date" name="to_date" value="<?php echo htmlspecialchars($toDate); ?>" required>
    <br><br>
    <button type="submit">View Reports</button>
</form>

    <hr>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($reportOutput)): ?>
        <?php echo $reportOutput; ?>
    <?php endif; ?>
            </div>
        </section>
        <!-- End Attendance Report Section -->
      </div>


    </section>
    <!-- End Attendance Report Section -->


  <!-- End Main Content -->

  <!-- ======= Back to Top Button ======= -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="arrow-up-short"></i></a>

  <!-- ======= Profile Picture Modal ======= -->
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
      }
    }

    function showViewStudents() {
      var viewStudentsSection = document.getElementById("viewStudentsContent");
      var manageAttendanceSection = document.getElementById("manageAttendanceContent");
      document.getElementById('viewRequestsContent').style.display = 'none';
      // Display viewStudentsSection and hide manageAttendanceSection
      addAttendanceForm.style.display="none";
      viewStudentsSection.style.display = "block";
      manageAttendanceSection.style.display = "none";
      attendanceReportContent.style.display="none";
       

      // Update active class in sidebar links
      var sidebarLinks = document.querySelectorAll(".sidebar ul li a");
      sidebarLinks.forEach(link => {
        link.classList.remove("active");
      });
      var viewStudentsLink = document.querySelector(".sidebar ul li a[href='#viewStudentsContent']");
      viewStudentsLink.classList.add("active");
    }

    function showManageAttendance() {
      var viewStudentsSection = document.getElementById("viewStudentsContent");
      var manageAttendanceSection = document.getElementById("manageAttendanceContent");
      document.getElementById('viewRequestsContent').style.display = 'none';
      document.getElementById('attendanceReportContent').style.display = 'none';
      // Display manageAttendanceSection and hide viewStudentsSection
      viewStudentsSection.style.display = "none";
    
      
      manageAttendanceSection.style.display = "block";

      // Update active class in sidebar links
      var sidebarLinks = document.querySelectorAll(".sidebar ul li a");
      sidebarLinks.forEach(link => {
        link.classList.remove("active");
      });
      var manageAttendanceLink = document.querySelector(".sidebar ul li a[href='#manageAttendanceContent']");
      manageAttendanceLink.classList.add("active");
    }
    // Add Attendance
// Show Add Attendance Form
function showAddAttendanceForm() {
    document.getElementById('addAttendanceSection').style.display = 'block';
}

// Add Attendance
document.getElementById('addAttendanceForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'add');

    fetch('manage_attendance.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        fetchFilteredAttendance(); // Optionally reload attendance records
        this.reset(); // Reset the form
        document.getElementById('addAttendanceSection').style.display = 'none'; // Hide the add attendance form
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

    // Edit Attendance
    function editAttendance(id, currentStatus) {
      const newStatus = prompt("Enter new status (Present/Absent/Late):", currentStatus);
      if (newStatus) {
        const formData = new FormData();
        formData.append('action', 'edit');
        formData.append('id', id);
        formData.append('status', newStatus);

        fetch('manage_attendance.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.text())
          .then(data => {
            alert(data);
            fetchFilteredAttendance(); // Optionally reload attendance records
          })
          .catch(error => {
            console.error('Error:', error);
          });
      }
    }

    // Delete Attendance
    function deleteAttendance(id) {
      if (!confirm('Are you sure you want to delete this attendance record?')) {
        return;
      }

      const formData = new FormData();
      formData.append('action', 'delete');
      formData.append('id', id);

      fetch('manage_attendance.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          alert(data);
          fetchFilteredAttendance(); // Optionally reload attendance records
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    // Fetch filtered attendance records
    function fetchFilteredAttendance() {
      let form = document.getElementById('filterAttendanceForm');
      let formData = new FormData(form);

      fetch('manage_attendance.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          console.log(data); // Debugging
          document.querySelector('#attendanceTable tbody').innerHTML = data;
        })
        .catch(error => {
          console.error('Error:', error);
        });

      return false; // Prevent form submission
    }

    // Function to show the View Requests section
function showViewRequests() {
  var viewRequestsSection = document.getElementById("viewRequestsContent");
  var viewStudentsSection = document.getElementById("viewStudentsContent");
  var manageAttendanceSection = document.getElementById("manageAttendanceContent");

  // Display viewRequestsSection and hide other sections
  attendanceReportContent.style.display="none";
    viewRequestsSection.style.display = "block";
  viewStudentsSection.style.display = "none";
  manageAttendanceSection.style.display = "none";


  // Update active class in sidebar links
  var sidebarLinks = document.querySelectorAll(".sidebar ul li a");
  sidebarLinks.forEach(link => {
    link.classList.remove("active");
  });
  var viewRequestsLink = document.querySelector(".sidebar ul li a[href='#viewRequestsContent']");
  viewRequestsLink.classList.add("active");

  // Fetch leave requests data and populate the table

}


// Function to show the Attendance Report section
function showAttendanceReport() {
    var attendanceReportSection = document.getElementById("attendanceReportContent");
    var viewStudentsSection = document.getElementById("viewStudentsContent");
    var manageAttendanceSection = document.getElementById("manageAttendanceContent");
    var viewRequestsSection = document.getElementById("viewRequestsContent");

    // Hide other sections
    if (viewStudentsSection) {
        viewStudentsSection.style.display = "none";
    }
    if (manageAttendanceSection) {
        manageAttendanceSection.style.display = "none";
    }
    if (viewRequestsSection) {
        viewRequestsSection.style.display = "none";
    }

    // Show attendanceReportContent section
    if (attendanceReportSection) {
        attendanceReportSection.style.display = "block";
    }

    // Update active class in sidebar links if needed
    var sidebarLinks = document.querySelectorAll(".sidebar ul li a");
    sidebarLinks.forEach(link => {
        link.classList.remove("active");
    });
    var attendanceReportLink = document.querySelector(".sidebar ul li a[href='#attendanceReportContent']");
    if (attendanceReportLink) {
        attendanceReportLink.classList.add("active");
    }

    
}



  </script>

</body>

</html>