<?php
session_start(); 


if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit();
}

$studentName = $_SESSION['student_name'];

include("../php/config.php");


$sql = "SELECT c.id as course_id, c.course_name, t.teacher_name 
        FROM teachers t
        INNER JOIN courses c ON t.course_id = c.id";
$result = $conn->query($sql);


$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnHub | Advanced Learning Ecosystem</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/home.css">
</head>
<body>

    <nav class="navbar">
        <div class="navbar-content">
        <div class="logo">Welcome <?php echo htmlspecialchars($studentName); ?></div>
        <div class="nav-links">
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#courses">Courses</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
                <a href="../index.php">LOGOUT</a>
            </div>
            
        </div>
    </nav>


    <section id="home" class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Redefine Your Learning Experience</h1>
                <p>Innovative digital learning platform designed to transform your skills and accelerate your professional growth through cutting-edge, personalized education solutions.</p>
                <div class="cta-buttons">
                    <a href="#courses" class="btn btn-primary">Explore Courses</a>
                    <a href="#services" class="btn btn-secondary">Our Services</a>
                </div>
            </div>
            <div class="hero-visual">
                <img src="../imgs/bg.jpg" alt="Modern Learning Experience">
            </div>
        </div>
    </section>


<section id="courses" class="section">
        <h2 class="section-title">Breakthrough Courses</h2>
        <div class="courses-grid">
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                        <div class="teacher-info">
                            <p class="teacher-name">Teacher: <?php echo htmlspecialchars($course['teacher_name']); ?></p>
                        </div>
                        <a href="coursepage.php?course_id=<?php echo $course['course_id']; ?>" class="btn btn-enroll">Enroll Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>




    <section id="services" class="section">
        <h2 class="section-title">Our Advanced Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <img src="../imgs/member.png" alt="Personalized Mentorship">
                <div class="card-content">
                    <h3>Expert Mentorship</h3>
                    <p>One-on-one guidance from industry professionals tailored to your unique learning path.</p>
                </div>
            </div>
            <div class="service-card">
                <img src="../imgs/member.png" alt="Interactive Workshops">
                <div class="card-content">
                    <h3>Interactive Workshops</h3>
                    <p>Hands-on, immersive learning experiences with real-world project implementations.</p>
                </div>
            </div>
            <div class="service-card">
                <img src="../imgs/member.png" alt="Career Development">
                <div class="card-content">
                    <h3>Career Acceleration</h3>
                    <p>Strategic career guidance and professional development support.</p>
                </div>
            </div>
            <div class="service-card">
                <img src="../imgs/member.png" alt="Resource Library">
                <div class="card-content">
                    <h3>Comprehensive Resources</h3>
                    <p>Extensive digital library with cutting-edge learning materials and research resources.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section contact">
        <h2 class="section-title">Connect With Us</h2>
        <form class="contact-form">
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <textarea rows="5" placeholder="Your Message" required></textarea>
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </section>


    <footer class="footer">
        <p>&copy; 2024 LearnHub. All Rights Reserved.</p>
    </footer>
</body>
</html>