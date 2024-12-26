<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch counts for teachers, courses, and students
$teacher_count_query = "SELECT COUNT(*) AS teacher_count FROM teachers";
$teacher_count_result = $conn->query($teacher_count_query);
$teacher_count = $teacher_count_result->fetch_assoc()['teacher_count'];

$course_count_query = "SELECT COUNT(*) AS course_count FROM courses";
$course_count_result = $conn->query($course_count_query);
$course_count = $course_count_result->fetch_assoc()['course_count'];

$student_count_query = "SELECT COUNT(*) AS student_count FROM students";
$student_count_result = $conn->query($student_count_query);
$student_count = $student_count_result->fetch_assoc()['student_count'];

// Fetch students for the table
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
    <style>
        .cards {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 20px;
        }

        .card {
            background-color: #2f2f2f;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 20px;
            width: 200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card h1 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .card h2 {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <!-- Side Navigation Bar -->
    <nav class="sidebar">
        <ul>
            <li><a href="./dashboard.php">Dashboard</a></li>
            <li><a href="./course.php">Courses</a></li>
            <li><a href="./teacher.php">Teachers</a></li>
            <li><a href="./note.php">Lecture Notes</a></li>
            <li><a href="../index.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Dashboard Content -->
    <div class="main-content">
        <header>
            <h1>Dashboard</h1>
        </header>

        <!-- Cards Section -->
        <div class="cards">
            <div class="card">
                <h1>No of Teachers</h1>
                <h2><?php echo $teacher_count; ?></h2>
            </div>

            <div class="card">
                <h1>No of Courses</h1>
                <h2><?php echo $course_count; ?></h2>
            </div>

            <div class="card">
                <h1>No of Students</h1>
                <h2><?php echo $student_count; ?></h2>
            </div>
        </div>

        <!-- Tables for Demo Data -->
        <div class="table-sections">
            <!-- Students Table -->
            <div class="table-section">
                <h2>Students</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Date of Birth</th>
                            <th>District</th>
                            <th>Email Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row['student_name'] . "</td>
                                        <td>" . $row['dob'] . "</td>
                                        <td>" . $row['district'] . "</td>
                                        <td>" . $row['email'] . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No students available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
