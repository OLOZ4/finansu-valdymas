<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['new-email'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $new_email, $user_id);

    if ($stmt->execute()) {
        echo "Email updated successfully!";
        echo '<script>
                setTimeout(function() {
                    window.location.href = "settings.html";
                }, 2000);
              </script>';
    } else {
        echo "Error updating email: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>