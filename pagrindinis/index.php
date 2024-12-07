<?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        header('Location: home.php');
        exit;
    }
    session_abort();
?>
<!DOCTYPE html>
<head>
    <title>PAGRINDINIS</title>
        <link rel="stylesheet" href="index.css">
</head>
<body>
    
    <div class="overlay">
        <img src="logo.png" alt="logo" class="logo">
        <div class="box">
            <div class="button-container">
                <a href="login.php">
                <button class="button" id="login-btn">LOGIN</button>
                </a>
                <a href="signup.php">
                <button class="button" id="signup-btn">SIGN UP</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
