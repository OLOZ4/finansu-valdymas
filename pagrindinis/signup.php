<?php
require_once("config.php");
$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $email = $_POST['email'];


    if($password == $confirm_password)
    {
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($query);
        if($result->num_rows == 0)
        {
            $query = "INSERT INTO users (username,email,password) VALUES ('$username', '$email', '$password')";
            $conn->query($query);
            exit;
        }
        else
        {
            echo '<h1>Username already exists</h1>';
        }
    }
    else
    {
        echo '<h1>Passwords do not match!</h1>';
    }
    if ($pageWasRefreshed)
    {
        echo 'puslapis atnaujintas!';
        exit;
    }
}
    
?>

<!DOCTYPE html>
<head>
    <title>Sign Up</title>
   <!-- <link rel="stylesheet" href="signupde.css"> -->
</head>
<body>

    <div class="overlay">
        <div class="signup-box">
            <h1>Sign Up</h1>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
                <div class="input-group"></div>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your usernane">
               </div>
        
                <div class="input-group">
                    <label for="name">First Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your first name">
                </div>

                <div class="input-group">
                    <label for="surname">Last Name</label>
                    <input type="text" id="surname" name="surname" required placeholder="Enter your last name">
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
