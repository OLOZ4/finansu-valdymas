<?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        header('Location: home.php');
        exit;
    }
    $toast_message = $_SESSION['toast_message'] ?? null;
    $toast_type = $_SESSION['toast_type'] ?? null;

    unset($_SESSION['toast_message']);
    unset($_SESSION['toast_type']);
?>
<!DOCTYPE html>
<head>
    <title>PAGRINDINIS</title>
        <link rel="stylesheet" href="index.css">
        <style>
        .toast {
            position: fixed;
            top: 20px;
            right: 200px;
            padding: 150px;
            border-radius: 5px;
            z-index: 10000;
            font-size: 14px;
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            font: bold;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.success {
            background-color: #4caf50;
            color: #ffffff;
        }

        .toast.error {
            background-color: #ff5c5c;
            color: #ffffff;
        }
    </style>
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
    <?php if ($toast_message): ?>
        <div class="toast <?php echo htmlspecialchars($toast_type); ?>" id="toast">
            <?php echo htmlspecialchars($toast_message); ?>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toast = document.getElementById('toast');
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            });
        </script>
    <?php endif; ?>
</body>
</html>
