<?php
require_once("config.php"); // Sitame faile yra saugomi prisijungimo prie db infomacija
header('Cache-Control: no-cache, must-revalidate');
$error_message = "";
$success_message = ""; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Jeigu html kode yra naudojamas post metodas

    $username = $_POST['username']; // Vartojo ivestas username prilyginamas kintamajam 'username'
    $password = $_POST['password']; // Vartojo ivestas password prilyginamas kintamajam 'password'
    $confirm_password = $_POST['confirm-password']; // Vartojo ivestas pakartotinis slaptazodis prilyginamas kintamajam 'confirm_password'
    $email = $_POST['email']; // Vartojo ivestas email prilyginamas kintamajam 'email'


    if($password == $confirm_password) // Jeigu abu vartotojo ivesti slaptazodziai sutampa tada:
        {
            $password_hash = password_hash($password,PASSWORD_BCRYPT); // Uzheshuojamas ivestas slaptazodis naudojant BCRYPT ir prilyginamas 'password_hash' kintamajam
           
            // Patikrinama ar yra jau egsistuojantis username
            ///*
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            //*/

            // Patikrinama ar yra jau egsistuojantis email
            ///*
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result1 = $stmt->get_result();
            //*/

            if($result->num_rows == 0 AND $result1->num_rows == 0) // Jei toks username ir email NEegzistuoja tada:
                {
                    // I db username email ir passwords irasyti vartotojo ivestus duomenis 
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $email, $password_hash);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result !== NULL)
                    {
                        


                        $success_message= 'Account creation was successful!'; // Jei  pavyko insertinti duomenis i db pranesti apie tai vartotojui
                        // Redirect to a desired page after 3 seconds
                        //html dalis, kuria galbut galima butu pagrazinti. dariau su chatgpt tai nzn ar good
                        echo '
                            <script>
                                let countdown = 3;
                                const countdownDisplay = setInterval(() => {
                                    document.getElementById("countdown").innerText = countdown--;
                                    if (countdown < 0) {
                                        clearInterval(countdownDisplay);
                                        window.location.href = "login.php"; // Redirect URL
                                    }
                                }, 1000);
                            </script>
                            <p>Redirecting in <span id="countdown">3</span> seconds...</p>
                        ';
                        //-----------This part of code is mandatory for update_income.php to work properly DO NOT MODIFY IT--------------//
                        
                        // Finding user ID when only knowing it's username

                        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $user_id = $row["id"];
                                break;
                            }
                        }
                        
                        $_SESSION['user_id'] = $user_id; // Setting user_id as session variable

                        // Inserting in income table data user id and income 0
                        $amount = 0.00;
                        
                        $stmt1 = $conn->prepare('INSERT INTO income (user_id, amount) VALUES (?, ?)');
                        $stmt1->bind_param('id', $user_id, $amount);
                        $stmt1->execute();
                        die();
                        // Execute query

                        if (!$stmt1->execute()) {
                            log_message("Error executing query: " . $stmt->error);
                            die('Query failed: ' . $stmt->error);
                        }
                        
                        //----------------------------------------------------------------------------------------------------------------//
                    } 
                        else echo 'Error: ' . $conn->error_get_last; // Kitaip ismesti klaida
                }
            else $error_message= '<h1>Username or E-mail already exists</h1></br></br>'; // Jei email ar username sutampa, apie tai pranesti vartotojui
        }
        else $error_message= '<h1>Passwords do not match!</h1>'; // Pranesti kad slaptazodziai nesutampa
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="overlay">
         <?php if (!empty($error_message) || !empty($success_message)): ?>
            <div class="alert <?php echo !empty($error_message) ? 'error' : 'success'; ?>">
            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
        <?php echo !empty($error_message) ? $error_message : $success_message; ?>
            </div>
        <?php endif; ?>
        
            <a href="index.php">
            <img src = "logo.png" alt="logo" class="logo">
            </a>
        
        
        <div class="signup-box">
            <h1>SIGN UP</h1>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <form id="signup-form" action="login.php" method="GET">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your username">
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <div class="input-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required placeholder="Confirm your password">
                </div>

                <button type="submit" class="button">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>
