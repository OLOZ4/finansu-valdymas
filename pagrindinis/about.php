<?php
header('Cache-Control: no-cache, must-revalidate');
session_start(); // Start the session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css" />
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <a href="home.php">
            <img src="logo.png" alt="Logo">    
            </a>
        </div>
        <div class="navbar-right">
            <button class="icon-btn" title="Settings" onclick="location.href='settings.php'">
                <i class="fa-solid fa-gear"></i>
            </button>
            <button class="icon-btn" title="Notifications" onclick="location.href='notifications.html'">
                <i class="fa-solid fa-bell"></i>
            </button>
            <a href="destroy_session.php">
            <button class="icon-btn logout-icon" title="Logout" onclick="location.href='logout.html'">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
            </a>
            <div class="navbar-profile"></div>
        </div>
    </div>
      <!--The 6 -->
     <div class="image-gallery">
        <div class="image-item">
            <img src="images/image1.jpg" alt="image1">
            <p class="name">TAURAS<br>BACKEND DEVELOPER</p>
        </div>
        <div class="image-item">
            <img src="images/image2.jpg" alt="image2">
            <p class="name">BENEDIKTAS<br>TEAM LEADER<br>FULL STACK DEVELOPER</p>
        </div>
        <div class="image-item">
            <img src="images/image3.jpg" alt="image3">
            <p class="name">DMITRIJ<br>
BACKEND DEVELOPER</p>
        </div>
        <div class="image-item">
            <img src="images/image4.jpg" alt="image4">
            <p class="name">ERVINAS<br>FRONTEND DEVELOPER</p>
        </div>
        <div class="image-item">
            <img src="images/image5.png" alt="image5">
            <p class="name">MARIJUS<br>
BACKEND DEVELOPER</p>
        </div>
        <div class="image-item">
            <img src="images/image6.jpg" alt="image6">
            <p class="name">GABIJA<br>FRONTEND DEVELOPER</p>
        </div>
    </div>
</body>
</html>
