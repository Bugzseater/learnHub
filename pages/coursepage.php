<?php
session_start(); // Start the session to access user data

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: ../login.php");
    exit();
}

$studentName = $_SESSION['student_name'];

// Get the course ID from the URL
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;
if (!$course_id) {
    // Redirect if no course ID is provided
    header("Location: ../index.php");
    exit();
}

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

// Fetch course and teacher information
$sql = "SELECT c.course_name, t.teacher_name 
        FROM teachers t
        INNER JOIN courses c ON t.course_id = c.id
        WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

// Fetch lecture notes for the course
$sql_notes = "SELECT * 
              FROM lecture_notes 
              WHERE course_id = ?";
$stmt_notes = $conn->prepare($sql_notes);
$stmt_notes->bind_param("i", $course_id);
$stmt_notes->execute();
$result_notes = $stmt_notes->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Page - LearnHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/course.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
        <div class="logo">Welcome <?php echo htmlspecialchars($studentName); ?></div>
        <div class="nav-links">
            <div class="nav-links">
                <a href="./home.php">Home</a>
                <a href="../index.php">LOGOUT</a>
            </div>
            
        </div>
    </nav>

    <!-- Course Notes Section -->
    <section id="notes" class="section">
        <h2 class="section-title">All Notes</h2>
        <div class="notes-grid">
            <?php while ($note = $result_notes->fetch_assoc()): ?>
                <div class="note-card">
                    <p>Uploaded on: <?php echo htmlspecialchars($note['upload_date']); ?></p>
                    <a href="../uploads/<?php echo htmlspecialchars($note['note_file']); ?>" download class="btn btn-download">Download</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>
</html>
