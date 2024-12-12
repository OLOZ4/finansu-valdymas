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
    <title>Profile Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css" />
    <link rel="stylesheet" href="settings.css">
    <style>
        body {
            background: linear-gradient(to top right, #0f2027, #203a43, #2c5364);
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        /* Navbaras */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4), 0 0 20px rgba(255, 255, 255, 0.3);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Navbaro mygtukai */
        .navbar button {
            background-color: #4e5d6c;
            color: #ffffff;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease-in-out;
        }

        .navbar button:hover {
            background-color: #6c7985; 
            color: #f1f1f1; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Icono mygtukai */
        .icon-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            font-size: 20px;
            transition: all 0.3s ease-in-out;
        }

        .icon-btn i {
            color: #ffffff; 
        }

        .icon-btn:hover i {
            color: #f1f1f1; 
            transform: scale(1.2); 
        }

        .logout-icon:hover i {
            color: #ff4d4d; 
            transform: scale(1.2); 
        }

        .navbar img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Profiline navabare */
        .navbar-profile {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #ffffff;
        }

        /* Containeriukai */
        .container {
            width: 550px;
            text-align: left;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Profilio nuotrauka */
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 10px;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit:cover;
            border-radius: 50%;
        }

        /* textas po profiliu */
                /* Style for the main button to open the pop-up */
        .change-profile-button {
            color: #ffffff;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
            background-color: #1f8cf8;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }

        .change-profile-button:hover {
            background-color: #1557bf;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .form-popup {
            display: none;
            position: fixed;
            overflow: hidden;
            top: 50%;
            left: 50%;
            z-index: 1000; /*kad butu virsutiniausias*/
            width: 400px;
            height: 500px;
            transform: translate(-50%, -50%);
            border: none;
            background-color: rgba(0, 0, 0, 0.1);
            padding: 1px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }

        /* Show the pop-up when the 'visible' class is added */
        .form-popup.visible {
            display: flex;
        }

        /* Pop-up content */
        .form-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            border-radius:10px;
            background: linear-gradient(to top right, #0f2027, #203a43, #2c5364);
        }

        /* Close button at the top-right */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .close-btn:hover {
            transform: scale(1.1);
        }

        /* Drag-and-drop area */
        .drag-drop-area {
            width: 80%;
            height: 60%;
            border: 2px dashed #ffffff;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Select from Files button */
        .file-select-btn {
            background-color: #1f8cf8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease-in-out;
            display: inline-block;
        }

        .file-select-btn:hover {
            background-color: #1557bf;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Hide the actual file input */
        input[type="file"] {
            display: none;
        }


        /* Info */
        .info-section {
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
            padding: 20px;
            border-radius: 30px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .info-text {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            font-size: 16px;
            margin: 0;
        }

        .info-instructions {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            line-height: 1.2;
        }

        /* mygtuku styling */
        .change-btn, .delete-btn {
            background-color: #1f8cf8;
            color: #ffffff;
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        /* Hoveris */
        .change-btn:hover {
            background-color: #1557bf; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .delete-btn {
            color: red;
        }

        /* Hoveris delete mygt */
        .delete-btn:hover {
            background-color: #bf1515; 
            color: #ffffff; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* atgal stylingas */
        .back-btn {
            background-color: #1f8cf8;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px; 
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: all 0.3s ease-in-out;
        }

        .back-btn:hover {
            background-color: #1557bf;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <!-- Navbaras -->
    <div class="navbar">
        <div class="navbar-left">
            <a href="home.php">
                <img src="logo.png" alt="Logo">
            </a>
            <button onclick="location.href='about.php'">About</button>
        </div>
        <div class="navbar-right">
            <!-- Iconos -->
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
            <div class="navbar-profile"></div> <!-- Profilio nuotrauka -->
        </div>
    </div>

    <!-- Main -->
    <div class="container" style="margin-top: 60px;">
        <!-- Profile Picture -->
        <div class="profile-pic">
            <img src="profile pictures\test.jpg"/>
        </div>
        <div>
            <button class="change-profile-button" onclick="document.getElementById('myForm').classList.add('visible')">Change profile picture</button>
        </div>
        
        <div class="form-popup" id="myForm">
            <div class="form-container">
                <!-- Close button -->
                <button class="close-btn" onclick="document.getElementById('myForm').classList.remove('visible')">X</button>

                <!-- Drag-and-drop area -->
                <label class="drag-drop-area">
                    Drag & Drop an image
                    <input type="file" accept="image/*" hidden>
                </label>

                <!-- Select from Files button -->
                <label class="file-select-btn">
                    Select from Files
                    <input type="file" accept="image/*" hidden>
                </label>
            </div>
        </div>


    <!-- User informacija -->
    <div class="info-section">
        <div class="info-text">
            <div class="info-label">Username</div>
            <div class="info-instructions">Pick a unique name others will see.</div>
        </div>
        <button class="change-btn" onclick="document.getElementById('usernameForm').classList.add('visible')">Change</button>
        <div class="form-popup" id="usernameForm">
            <div class="form-container">
                <!-- Close button -->
                <button class="close-btn" onclick="document.getElementById('usernameForm').classList.remove('visible')">X</button>
                <form action="change_username.php" method="post">
                    <label for="new-username">New Username:</label>
                    <input type="text" id="new-username" name="new-username" required>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-text">
            <div class="info-label">Email Address</div>
            <div class="info-instructions">Use a valid email for updates.</div>
        </div>
        <button class="change-btn" onclick="document.getElementById('emailForm').classList.add('visible')">Change</button>
        <div class="form-popup" id="emailForm">
            <div class="form-container">
                <!-- Close button -->
                <button class="close-btn" onclick="document.getElementById('emailForm').classList.remove('visible')">X</button>
                <form action="change_email.php" method="post">
                    <label for="new-email">New Email:</label>
                    <input type="email" id="new-email" name="new-email" required>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-text">
            <div class="info-label">Password</div>
            <div class="info-instructions">Use 8+ characters for security.</div>
        </div>
        <button class="change-btn" onclick="document.getElementById('passwordForm').classList.add('visible')">Change</button>
        <div class="form-popup" id="passwordForm">
            <div class="form-container">
                <!-- Close button -->
                <button class="close-btn" onclick="document.getElementById('passwordForm').classList.remove('visible')">X</button>
                <form action="change_password.php" method="post">
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new-password" required>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-text">
            <div class="info-label">Delete your account</div>
            <div class="info-instructions">Confirm deletion carefully.</div>
        </div>
        <button class="delete-btn" onclick="document.getElementById('deleteForm').classList.add('visible')">Delete</button>
        <div class="form-popup" id="deleteForm">
            <div class="form-container">
                <!-- Close button -->
                <button class="close-btn" onclick="document.getElementById('deleteForm').classList.remove('visible')">X</button>
                <form action="delete_account.php" method="post">
                    <label for="confirm-delete">Type "DELETE" to confirm:</label>
                    <input type="text" id="confirm-delete" name="confirm-delete" required>
                    <button type="submit" class="submit-btn">Delete</button>
                </form>
            </div>
        </div>
    </div>

        <!-- Back to Main mygtukas -->
        <button class="back-btn" onclick="location.href='home.php'">Back to Main</button>
    </div>
    <script src="settings.js"></script>
</body>
</html>