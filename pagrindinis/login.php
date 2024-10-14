<?php
  // Include the config file
  require_once('config.php');

  // Handle the login form submission
  if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Patikrina ar html kode yra method=POST 
    $user = $_POST['username']; // Prilygina user kinamajam vartotojo ivesta username arba email formoje
    $symbol = "@"; // Naudojamas atskirti ar vartotojas ivede username ar email
    $password = $_POST['password']; // Prilygina password kinamajam vartotojo ivesta password formoje
    

    // Patikrina ar vartotojas ivede savo username ar email, tai yra svarbu, nes kreipiantis i duombaze reikia zinoti ar ivesta vartotojo reiksme reikia tikrinti su email ar su username
    if (strpos($user,$symbol) == true) $kintamasis = 'email'; // Issiaiskina ar vartotojas ivede username ar email patirindamas ar kintamajame yra "@" zenkliukas, jei taip, kintamaji "kintamasis" prilygina reiksmei "email"
    else $kintamasis = 'username'; // Jei nera "@" kintamasis "kintamasis" yra prilyginamas username

    // Patikrina ar egzistuoja toks username ar email  
    $result = $conn->query("SELECT * FROM users WHERE $kintamasis='$user'"); // Suformuojama uzklausa i db ir kintamajam "result" prilyginamas uzklausos atsakymas 

    if ($result->num_rows > 0) // Jeigu nors yra rastas nors vienas atitikmuo tada:
    { 
      while ($row = $result->fetch_assoc()) { // Kintamajam "row" prilygina eilutes masyva, kurios username ar email buvo ivestas (mysqli_fetch_assoc — Fetch the next row of a result set as an associative array)
        $hashed_password = $row['password']; // Is tos eilutes gautos paimamas slaptazodis, db saugomas stulpelyje 'password' ir prilyginamas kintamajam "hashed password"         
      }
      if (password_verify($password,$hashed_password)) // Patikrinama ar vartotojo suvestas slaptazodis ir hash'as saugomas db sutampa
      {
        header('Location: dashboard.php'); // Jei sutampa, tai perforwardina zmogu i puslapi dashboard.php 

      }
      else echo 'Invalid username or password'; // Jei slaptazodis su hashu nesutampa, tada ismetamas error'as
    }
    else echo 'Invalid username or password'; // Jei nerastas db vartotojo ivestas username ar email
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="logdesing.css">
</head>
<body>
    <div class="overlay">
        <div class="login-box">
            <h2>Login</h2>
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
                <button type="button" class="button forgot-password">Forgot Password?</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

