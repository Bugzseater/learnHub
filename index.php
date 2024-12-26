<?php
session_start(); // Start the session to store login info

// Database connection
$host = 'localhost'; // Database host
$username = 'root'; // Database username
$password = ''; // Database password
$dbname = 'lms'; // Database name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentNo = $_POST['student_no'];
    $userPassword = $_POST['password'];

    if ($studentNo == "Admin" && $userPassword == "admin123") {
        // Redirect admin to the dashboard
        header("Location: ./pages/dashboard.php");
        exit();
    }

    // Fetch user data based on student number (email or student number)
    $sql = "SELECT id, student_name, email, password FROM students WHERE email = '$studentNo' OR student_name = '$studentNo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password using password_verify
        if (password_verify($userPassword, $row['password'])) {
            // Store user information in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['student_name'] = $row['student_name']; // Store student name
            $_SESSION['email'] = $row['email'];
            // Redirect to home.php
            header("Location: ./pages/home.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with that student number or email.";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Glassmorphism Login</title>
    <link rel="stylesheet" href="./styles/login.css">
</head>
<body>
    <div class="container">

        <div class="title">
            <h3>Welcome to our LMS</h3>
        </div>

        <div class="content">
            <div class="left">
                <img src="./imgs/student.png" alt="Student Illustration">
            </div>

            <div class="right">
                <!-- Display error message if any -->
                <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

                <form action="index.php" method="POST">
                    <div class="inputs">
                        <label for="stno">Student No or Email</label>
                        <input type="text" id="stno" name="student_no" placeholder="Enter your student number or email" required>
                    </div>

                    <div class="inputs">
                        <label for="pws">Password</label>
                        <input type="password" id="pws" name="password" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="button">LOGIN</button>
                    <p>Don't Have an Account? <a href="./pages/register.php">Register</a></p>
                </form>
            </div>
        </div>

    </div>
</body>
</html>
