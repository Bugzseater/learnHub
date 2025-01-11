<?php
session_start();
include("../php/config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === "add") {
        $teacher_name = $_POST['teacher_name'];
        $email = $_POST['email'];
        $cno = $_POST['contact_no'];
        $course_id = $_POST['course_id'];
        $sql = "INSERT INTO teachers (teacher_name, email, contact_no, course_id) 
                VALUES ('$teacher_name', '$email', '$cno', '$course_id')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "New teacher added successfully.";
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($action === "delete" && isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM teachers WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Teacher deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting teacher: " . $conn->error;
        }
    } elseif ($action === "update" && isset($_POST['id'])) {
        $id = $_POST['id'];
        $teacher_name = $_POST['teacher_name'];
        $email = $_POST['email'];
        $cno = $_POST['contact_no'];
        $course_id = $_POST['course_id'];
        $sql = "UPDATE teachers SET teacher_name='$teacher_name', email='$email', contact_no='$cno', course_id='$course_id' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Teacher updated successfully.";
        } else {
            $_SESSION['message'] = "Error updating teacher: " . $conn->error;
        }
    }
    header("Location: teacher.php");
    exit();
}
$teachers_sql = "SELECT teachers.*, courses.course_name FROM teachers 
                 LEFT JOIN courses ON teachers.course_id = courses.id";
$teachers = $conn->query($teachers_sql);
$courses_sql = "SELECT * FROM courses";
$courses = $conn->query($courses_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
    <link rel="stylesheet" href="../styles/teacher.css">
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>
    <div class="dashboard-container">
        <nav class="sidebar">
            <ul>
                <li><a href="./dashboard.php">Dashboard</a></li>
                <li><a href="./course.php">Courses</a></li>
                <li><a href="./teacher.php">Teachers</a></li>
                <li><a href="./note.php">Lecture Notes</a></li>
                <li><a href="../index.php">Logout</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <header>
                <h1>Teachers</h1>
            </header>
            <div class="form-sections">
                <button onclick="openModal('add')">Add Teacher</button>
            </div>
            <div class="table-sections">
                <h2>Teachers</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Teacher Name</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Course</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($teachers->num_rows > 0) {
                            while ($row = $teachers->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row['teacher_name'] . "</td>
                                        <td>" . $row['email'] . "</td>
                                        <td>" . $row['contact_no'] . "</td>
                                        <td>" . ($row['course_name'] ?? 'N/A') . "</td>
                                        <td>
                                            <button onclick=\"openModal('update', {id: '" . $row['id'] . "', name: '" . $row['teacher_name'] . "', email: '" . $row['email'] . "', contact: '" . $row['contact_no'] . "', course: '" . $row['course_id'] . "'})\">Update</button>
                                            <form method='POST' action='teacher.php' style='display:inline;'>
                                                <input type='hidden' name='action' value='delete'>
                                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                                <button type='submit'>Delete</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No teachers available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="courseModal" style="display:none; position:fixed; top:50%; left:50%; height:500px; transform:translate(-50%, -50%); background:white; padding:20px; box-shadow:0 4px 8px rgba(0, 0, 0, 0.1);">
        <form method="POST" action="teacher.php">
            <input type="hidden" name="action" id="formAction">
            <input type="hidden" name="id" id="teacherId">
            <input type="text" name="teacher_name" id="teacherName" placeholder="Teacher Name" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="text" name="contact_no" id="cno" placeholder="Contact Number" required>
            <select name="course_id" id="course" required>
                <option value="">-- Select a Course --</option>
                <?php
                while ($course = $courses->fetch_assoc()) {
                    echo "<option value='" . $course['id'] . "'>" . $course['course_name'] . "</option>";
                }
                ?>
            </select>
            <button type="submit">Save</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
    <script>
        function openModal(action, teacher = {}) {
            document.getElementById('formAction').value = action;
            document.getElementById('teacherId').value = teacher.id || '';
            document.getElementById('teacherName').value = teacher.name || '';
            document.getElementById('email').value = teacher.email || '';
            document.getElementById('cno').value = teacher.contact || '';
            document.getElementById('course').value = teacher.course || '';
            document.getElementById('courseModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('courseModal').style.display = 'none';
        }
    </script>
</body>
</html>