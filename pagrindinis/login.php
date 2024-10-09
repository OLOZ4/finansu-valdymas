<?php
  // Include the config file
  require_once('config.php');

  // Handle the login form submission
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are correct
    $query = "SELECT * FROM users WHERE username='$user' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      // Login successful, redirect to the dashboard
      header('Location: dashboard.php');
      exit;
    }
    else {
      $query = "SELECT * FROM users WHERE email='$user' AND password='$password'";
      $result = $conn->query($query);
      if ($result->num_rows > 0) {
        // Login successful, redirect to the dashboard
        header('Location: dashboard.php');
        exit;
      }
      else
      {
      // Login failed, display an error message
      echo 'Invalid username or password'; 
      }

    }
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

