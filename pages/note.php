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

$teachers_sql = "SELECT * FROM teachers";
$teachers = $conn->query($teachers_sql);

$courses_sql = "SELECT * FROM courses";
$courses = $conn->query($courses_sql);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $teacher_id = $_POST['teacher_id'];
    $course_id = $_POST['course_id'];
    $upload_date = $_POST['upload_date'];
    $note_file = $_FILES['note_file'];

    // File upload logic
    if ($note_file['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/"; // Directory to store uploaded files
        $file_name = basename($note_file['name']);
        $file_name = preg_replace("/[^a-zA-Z0-9\-_\.]/", "_", $file_name);  // Sanitize file name
        $target_file = $target_dir . $file_name;

        // Check if file is a valid file type
        $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'txt'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        if (in_array(strtolower($file_type), $allowed_types)) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($note_file['tmp_name'], $target_file)) {
                // Check if teacher ID exists in the `teachers` table
                $teacher_check_sql = "SELECT * FROM teachers WHERE id = ?";
                $stmt = $conn->prepare($teacher_check_sql);
                $stmt->bind_param("i", $teacher_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Teacher exists, insert data into the database
                    $stmt = $conn->prepare("INSERT INTO lecture_notes (teacher_id, course_id, upload_date, note_file) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiss", $teacher_id, $course_id, $upload_date, $target_file);

                    if ($stmt->execute()) {
                        echo "<script>alert('Note uploaded successfully!');</script>";
                    } else {
                        echo "<script>alert('Error: " . $stmt->error . "');</script>";
                    }

                    $stmt->close();
                } else {
                    echo "<script>alert('Error: Teacher with ID $teacher_id does not exist.');</script>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only PDF, DOC, DOCX, PPT, and TXT files are allowed.');</script>";
        }
    } else {
        echo "<script>alert('Error uploading file.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture Notes</title>
    <link rel="stylesheet" href="../styles/dashboard.css">

    <style>

    /* Form Styling */
    form {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 500px;
        margin: 0 auto;
    }

    /* Input Fields Styling */
    input[type="text"],
    input[type="email"],
    select,
    input[type="date"],
    input[type="file"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        background-color: #f9f9f9;
        color: #333;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    /* Focus Styling for Inputs */
    input[type="text"]:focus,
    input[type="email"]:focus,
    select:focus,
    input[type="date"]:focus,
    input[type="file"]:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        outline: none;
    }

    /* Buttons Styling */
    button[type="submit"],
    button[type="reset"] {
        width: 48%;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin: 10px 0;
    }

    /* Submit Button */
    button[type="submit"] {
        background-color: #3498db;
        color: white;
    }

    button[type="submit"]:hover {
        background-color: #2980b9;
    }

    /* Reset Button */
    button[type="reset"] {
        background-color: #e74c3c;
        color: white;
    }

    button[type="reset"]:hover {
        background-color: #c0392b;
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 768px) {
        .dashboard-container {
            flex-direction: column;
        }

        .main-content {
            margin-left: 0;
            padding: 15px;
        }

        /* Form in smaller screens */
        form {
            width: 100%;
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 100%;
            position: relative;
            padding: 10px;
        }

        .sidebar ul li {
            font-size: 16px;
        }
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
                <h1>Lecture Notes</h1>
            </header>

            <form method="POST" action="note.php" enctype="multipart/form-data">
                <select name="teacher_id" id="teacherName" required>
                    <option value="">-- Select a Teacher --</option>
                    <?php
                    while ($teacher = $teachers->fetch_assoc()) {
                        echo "<option value='" . $teacher['id'] . "'>" . $teacher['teacher_name'] . "</option>";
                    }
                    ?>
                </select>
                <select name="course_id" id="courseName" required>
                    <option value="">-- Select a Course --</option>
                    <?php
                    while ($course = $courses->fetch_assoc()) {
                        echo "<option value='" . $course['id'] . "'>" . $course['course_name'] . "</option>";
                    }
                    ?>
                </select>
                <input type="date" name="upload_date" id="uploadDate" required>
                <input type="file" name="note_file" id="noteFile" required>
                <button type="submit">Save</button>
                <button type="reset">Clear</button>
            </form>
        </div>
    </div>

</body>
</html>
