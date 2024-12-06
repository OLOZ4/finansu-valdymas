<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new-password'], PASSWORD_BCRYPT);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $user_id);

    if ($stmt->execute()) {
        echo "Password updated successfully!";
        echo '<script>
                setTimeout(function() {
                    window.location.href = "settings.html";
                }, 2000);
              </script>';
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>