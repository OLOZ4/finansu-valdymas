<?php
require_once("config.php"); // Sitame faile yra saugomi prisijungimo prie db infomacija

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Jeigu html kode yra naudojamas post metodas

    $username = $_POST['username']; // Vartojo ivestas username prilyginamas kintamajam 'username'
    $password = $_POST['password']; // Vartojo ivestas password prilyginamas kintamajam 'password'
    $confirm_password = $_POST['confirm-password']; // Vartojo ivestas pakartotinis slaptazodis prilyginamas kintamajam 'confirm_password'
    $email = $_POST['email']; // Vartojo ivestas email prilyginamas kintamajam 'email'


    if($password == $confirm_password) // Jeigu abu vartotojo ivesti slaptazodziai sutampa tada:
        {
            $password_hash = password_hash($password,PASSWORD_BCRYPT); // Uzheshuojamas ivestas slaptazodis naudojant BCRYPT ir prilyginamas 'password_hash' kintamajam
           
            $result = $conn->query("SELECT * FROM users WHERE username='$username'"); // Patikrinama ar yra jau egsistuojantis username

            $result1 = $conn->query("SELECT * FROM users WHERE email='$email'"); // Patikrinama ar yra jau egsistuojantis email

            if($result->num_rows == 0 AND $result1->num_rows == 0) // Jei toks username ir email NEegzistuoja tada:
                {
                    $result = $conn->query("INSERT INTO users (username,email,password) VALUES ('$username', '$email', '$password_hash')"); // I db username email ir passwords irasyti vartotojo ivestus duomenis 
                    if ($result) echo 'Account creation was successful!'; // Jei  pavyko insertinti duomenis i db pranesti apie tai vartotojui
                        else echo 'Error: ' . $conn->error_get_last; // Kitaip ismesti klaida
                }
            else echo '<h1>Username or E-mail already exists</h1></br></br>'; // Jei email ar username sutampa, apie tai pranesti vartotojui
        }
        else echo '<h1>Passwords do not match!</h1>'; // Pranesti kad slaptazodziai nesutampa
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
        <div class="signup-box">
            <h1>SIGN UP</h1>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
              <form id="signup-form" action="home.html" method="GET">
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
   
                
