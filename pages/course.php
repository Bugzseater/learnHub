<?php
session_start();


include("../php/config.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === "add") {
        $course_name = $_POST['course_name'];
        $course_description = $_POST['course_description'];
        $course_duration = $_POST['course_duration'];

        $sql = "INSERT INTO courses (course_name, course_description, course_duration) 
                VALUES ('$course_name', '$course_description', '$course_duration')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "New course added successfully.";
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($action === "delete" && isset($_POST['id'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM courses WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Course deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting course: " . $conn->error;
        }
    } elseif ($action === "update" && isset($_POST['id'])) {
        $id = $_POST['id'];
        $course_name = $_POST['course_name'];
        $course_description = $_POST['course_description'];
        $course_duration = $_POST['course_duration'];

        $sql = "UPDATE courses SET course_name='$course_name', course_description='$course_description', course_duration='$course_duration' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Course updated successfully.";
        } else {
            $_SESSION['message'] = "Error updating course: " . $conn->error;
        }
    }

   
    header("Location: course.php");
    exit();
}


$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
    <link rel="stylesheet" href="../styles/courseadmin.css">
    

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
                <h1>Courses</h1>
            </header>

            <div class="form-sections">
                <button onclick="openModal('add')">Add Course</button>
            </div>


            <div class="table-sections">

                <div class="table-section">
                    <h2>Courses</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Description</th>
                                <th>Duration (months)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $row['course_name'] . "</td>
                                            <td>" . $row['course_description'] . "</td>
                                            <td>" . $row['course_duration'] . "</td>
                                            <td>
                                                <button onclick=\"openModal('update', {id: '" . $row['id'] . "', name: '" . $row['course_name'] . "', description: '" . $row['course_description'] . "', duration: '" . $row['course_duration'] . "'})\">Update</button>
                                                <form method='POST' action='course.php' style='display:inline;'>
                                                    <input type='hidden' name='action' value='delete'>
                                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                                    <button type='submit'>Delete</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No courses available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

 
    <div id="courseModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; box-shadow:0 4px 8px rgba(0, 0, 0, 0.1);">
        <form method="POST" action="course.php">
            <input type="hidden" name="action" id="formAction">
            <input type="hidden" name="id" id="courseId">
            <input type="text" name="course_name" id="courseName" placeholder="Course Name" required>
            <input type="text" name="course_description" id="courseDescription" placeholder="Course Description" required>
            <input type="text" name="course_duration" id="courseDuration" placeholder="Course Duration" required>
            <button type="submit">Save</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>


    <script>
        function openModal(action, course = null) {
            const modal = document.getElementById('courseModal');
            const formAction = document.getElementById('formAction');

            if (action === 'update' && course) {
                document.getElementById('courseId').value = course.id;
                document.getElementById('courseName').value = course.name;
                document.getElementById('courseDescription').value = course.description;
                document.getElementById('courseDuration').value = course.duration;
            } else {
                document.getElementById('courseId').value = "";
                document.getElementById('courseName').value = "";
                document.getElementById('courseDescription').value = "";
                document.getElementById('courseDuration').value = "";
            }

            formAction.value = action;
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('courseModal').style.display = 'none';
        }
    </script>
    
</body>
</html>
