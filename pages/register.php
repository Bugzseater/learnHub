<?php
include("../php/config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $studentName = $_POST['student_name'];
    $dob = $_POST['dob'];
    $district = $_POST['district'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $sql = "INSERT INTO students (student_name, dob, district, email, password)
            VALUES ('$studentName', '$dob', '$district', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Registration Successful!');
                window.location.href = '../index.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Glassmorphism Register</title>
    <link rel="stylesheet" href="../styles/register.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <h3>Welcome to our LMS</h3>
        </div>
        <div class="content">
            <div class="right">
                <form id="registerForm" action="register.php" method="POST">
                    <div class="inputs">
                        <label for="stno">Student Name</label>
                        <input type="text" id="stno" name="student_name" placeholder="Enter your student name" required>
                    </div>
                    <div class="inputs">
                        <label for="dob">Date of birth</label>
                        <input type="date" id="dob" name="dob" placeholder="Select your birthday" required>
                    </div>
                    <div class="inputs">
                        <label for="district">District</label>
                        <input type="text" id="district" name="district" placeholder="Select your District" required>
                    </div>
                    <div class="inputs">
                        <label for="email">E-mail Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your Email Address" required>
                    </div>
                    <div class="inputs">
                        <label for="pws">Password</label>
                        <input type="password" id="pws" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="button">REGISTER</button>
                    <p>You Have an Account? <a href="../index.php">Login</a></p>
                </form>
            </div>
            <div class="left">
                <img src="../imgs/student.png" alt="Student Illustration">
            </div>
        </div>
    </div>
</body>
</html>
