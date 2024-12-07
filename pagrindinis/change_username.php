<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['new-username'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param("si", $new_username, $user_id);

    if ($stmt->execute()) {
        $_SESSION['username'] = $new_username;
        echo "Username updated successfully!";
        echo '<script>
                setTimeout(function() {
                    window.location.href = "settings.php";
                }, 2000);
              </script>';
    } else {
        echo "Error updating username: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>