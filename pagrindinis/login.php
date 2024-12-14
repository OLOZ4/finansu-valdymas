<?php
  // Include the config file
  require_once('config.php');
  header('Cache-Control: no-cache, must-revalidate');
  $error_message = '';
  // Handle the login form submission
  if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Patikrina ar html kode yra method=POST 
    session_start(); // startuojama sesija
    $user = $_POST['username']; // Prilygina user kinamajam vartotojo ivesta username arba email formoje
    $symbol = "@"; // Naudojamas atskirti ar vartotojas ivede username ar email
    $password = $_POST['password']; // Prilygina password kinamajam vartotojo ivesta password formoje

    // Patikrina ar vartotojas ivede savo username ar email, tai yra svarbu, nes kreipiantis i duombaze reikia zinoti ar ivesta vartotojo reiksme reikia tikrinti su email ar su username
    if (strpos($user,$symbol) == true) $kintamasis = 'email'; // Issiaiskina ar vartotojas ivede username ar email patirindamas ar kintamajame yra "@" zenkliukas, jei taip, kintamaji "kintamasis" prilygina reiksmei "email"
    else $kintamasis = 'username'; // Jei nera "@" kintamasis "kintamasis" yra prilyginamas username

    // Patikrina ar egzistuoja toks username ar email  
    // Suformuojama uzklausa i db ir kintamajam "result" prilyginamas uzklausos atsakymas 
    $stmt = $conn->prepare("SELECT * FROM users WHERE $kintamasis = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) // Jeigu nors yra rastas nors vienas atitikmuo tada:
    { 
      while ($row = $result->fetch_assoc()) { // Kintamajam "row" prilygina eilutes masyva, kurios username ar email buvo ivestas (mysqli_fetch_assoc — Fetch the next row of a result set as an associative array)
        $hashed_password = $row['password']; // Is tos eilutes gautos paimamas slaptazodis, db saugomas stulpelyje 'password' ir prilyginamas kintamajam "hashed password"         
      }
      if (password_verify($password,$hashed_password)) // Patikrinama ar vartotojo suvestas slaptazodis ir hash'as saugomas db sutampa
      {
        //------------DO NOT MODIFY IT--------------------------------//
        // Finding user ID when only knowing it's username

        $stmt = $conn->prepare("SELECT id FROM users WHERE $kintamasis = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user_id = $row["id"];
                break;
            }
        }
        
        $_SESSION['user_id'] = $user_id; // Setting user_id as session variable
        $_SESSION['logged_in'] = true;

        //-------------------------------------------------------------------//
        
        header('Location: home.php'); // Jei sutampa, tai perforwardina zmogu i puslapi dashboard.php 
        if ($kintamasis == 'email')
        {
          // Suformuojama uzklausa i db ir kintamajam "result" prilyginamas uzklausos atsakymas 
          $stmt = $conn->prepare("SELECT username FROM users WHERE $kintamasis = ?");
          $stmt->bind_param("s", $user);
          $stmt->execute();
          $result = $stmt->get_result();
          if ($result->num_rows > 0) 
          {
            while ($row = $result->fetch_assoc()) 
            {
              $_SESSION['username'] = $row['username'];
              break;
            }
          }
        }
        else $_SESSION['username'] = $_POST['username']; // session username
        
      }

      else $error_message= 'Invalid username or password'; // Jei slaptazodis su hashu nesutampa, tada ismetamas error'as
    }
    else $error_message='Invalid username or password'; // Jei nerastas db vartotojo ivestas username ar email
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="overlay">
      <?php if(!empty($error_message)): ?>
        <div class="alert">
            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
          <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        <div class="login-container">
            <a href="index.php">
            <img src = "logo.png" alt="logo" class="logo">
            </a>
            <div class="login-box">
                <h2>LOGIN</h2>
                <form id="loginForm" action="login.php" method="post">
                    <div class="input-group">
                        <label for="username">Username or email</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="button login-submit">Submit</button>
                    <div class="input-group"></div>
                    <a href="password_reset.php">
                        <button type="button" class="button forgot-password">Forgot Password?</button>
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

