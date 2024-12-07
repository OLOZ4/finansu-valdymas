<?php
require_once("config.php");

//uzklausa kuri tikrina ar email egzistuoja ir issiuncia i ta email tam tikra token, su kuriuo po to keis slaptazodi
if ($_SERVER['REQUEST_METHOD']=='POST')
{
    $email=$_POST['email'];//gaunamas ivestas email

    //sujungiamas ivestas email su egzistuojanciu duomenu bazeje
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)//jei yra rysys (egzistuoja toks email), yra eilute su vartotoju
    {

        //uzklausa, kuri keicia slaptazodi
        $new_password = $_POST['new-password'];
        $confirm_password = $_POST['confirm-password'];

        if($new_password === $confirm_password)//patikriname ar sutampa
            {
            $password_hash = password_hash($new_password, PASSWORD_BCRYPT);//uzhashuojam
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
            $stmt->bind_param("ss", $password_hash, $email);
            $stmt->execute();
            echo 'Password reset successful! Redirecting in 3 seconds...';//pranesam kad pavyko, redirectinam po keliu sekundziu
            
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
            }
        else echo 'Passwords do not match!';
    } 
    else echo 'Email not found!'; //jei nerastas pranesti kad nerastas ir nedaryt operaciju
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="password_reset.css">
</head>
<body>

    <div class="overlay">
        <div class="forgot-password-box">
            <h2>Reset Password</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <br>
                <div class="input-group">
                <label for="email">Email Address:</label> 
                <input type="email" id="email" name="email" required placeholder="Address@email">
                </div>

                <div class="input-group">
                <label for="new-password">New Password:</label> 
                <input type="password" id="new-password" name="new-password" required placeholder="New password">
                </div>

                <div class="input-group">
                <label for="confirm-password">Confirm New Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required placeholder="Confirm new password">
                </div>

                <button type="submit" class="button">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
