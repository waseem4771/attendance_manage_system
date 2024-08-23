<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <style>
    /* Paste your CSS styles here */
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
      text-align: center;
    }

    .heading {
      text-align: center;
      color: black;
    }

    .form-group {
      margin-bottom: 20px;
    }

    select,
    input[type="text"],
    input[type="tel"],
    input[type="email"],
    input[type="number"],
    input[type="password"],
    input[type="submit"],
    input[type="button"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #dddfe2;
      border-radius: 5px;
      background-color: #f0f2f5;
      font-size: 14px;
      color: #1c1e21;
      outline: none;
    }

    select {
      width: 106.5%;
    }

    input[type="submit"],
    input[type="button"] {
      background-color: black;
      color: white;
      text-transform: uppercase;
      cursor: pointer;
      font-weight: bold;
      transition: var(--transition-timing);
    }

    input[type="submit"]:hover,
    input[type="button"]:hover {
      background-color: #FF91A4;
    }

    button:hover {
      font-weight: bold;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: white;
      font-size: 14px;
      outline: none;
      color: blue;
      cursor: pointer;
      border-color: white;
      transition: var(--transition-timing);
    }

    p {
      text-align: center;
    }

    .error-message {
      color: #dc143c;
      font-size: 14px;
      text-align: center;
      margin-top: 10px;
    }

    .password-strength {
      font-size: 12px;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <div class="wrap">
    <h2 class="heading">Registration Form</h2>

    <form action="submit-registration.php" method="POST">
      <div class="form-group">
        <input type="text" name="fullname" placeholder="Full Name" required>
      </div>

      <div class="form-group">
        <input type="text" name="rollno" placeholder="Roll Number" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <div class="form-group">
        <input type="text" name="department" placeholder="Department" required>
      </div>

      <div class="form-group">
        <input type="tel" name="phone" placeholder="Phone Number" required>
      </div>

      <div class="form-group">
        <input type="text" name="homeaddress" placeholder="Home Address" required>
      </div>

      <div class="form-group">
        <input type="email" name="email" placeholder="Email" required>
      </div>

      <div class="form-group">
        <input type="number" name="age" placeholder="Age">
      </div>
      <div class="form-group">
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>

  

      <div class="form-group">
        <input type="submit" value="Register">
      </div>
      <p>or</p>
      <button type="button" onclick="window.location.href='/userLogin.php'">Already have an Account</button>
    </form>

    <p class="error-message"></p>
    <p class="password-strength"></p>
  </div>




  <?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (!empty($_POST['fullname']) && !empty($_POST['rollno']) && !empty($_POST['department']) && !empty($_POST['phone']) && !empty($_POST['homeaddress']) && !empty($_POST['email']) && isset($_POST['gender']) && isset($_POST['age'])) {
        // Sanitize and validate input data
        $fullname = htmlspecialchars($_POST['fullname']);
        $rollno = htmlspecialchars($_POST['rollno']);
        $password = htmlspecialchars($_POST['password']);
        $department = htmlspecialchars($_POST['department']);
        $phone = htmlspecialchars($_POST['phone']);
        $homeaddress = htmlspecialchars($_POST['homeaddress']);
        $email = htmlspecialchars($_POST['email']);
        $gender = htmlspecialchars($_POST['gender']);
        $age = (int)$_POST['age']; // Assuming age is sent as an integer from the form

        // Validate email format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Database connection parameters
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "attendancesystem";

            // Create connection
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Insert student data into the database
            $sql = "INSERT INTO students (name, rollno, password, department, phoneno, homeaddress, email, gender, age)
                    VALUES ('$fullname', '$rollno', '$password','$department', '$phone', '$homeaddress', '$email', '$gender', '$age')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            // Close connection
            $conn->close();
        } else {
            echo "Invalid email format";
        }
    } else {
        echo "All fields are required";
    }
}
?>


</body>
</html>
