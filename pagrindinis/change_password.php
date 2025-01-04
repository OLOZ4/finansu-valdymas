<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new-password'], PASSWORD_BCRYPT);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $user_id);

    if ($stmt->execute()) {
        $_SESSION['toast_message'] = "Password updated successfully!";
        $_SESSION['toast_type'] = "success"; // 'success' or 'error'
    } else {
        $_SESSION['toast_message'] = "Error updating password: " . $conn->error;
        $_SESSION['toast_type'] = "error"; 
    }

    $stmt->close();
    $conn->close();
    
    header("Location: settings.php");
    exit();
}
?>
