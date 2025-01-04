<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['new-email'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $new_email, $user_id);

    if ($stmt->execute()) {
        $_SESSION['toast_message'] = "Email updated successfully!";
        $_SESSION['toast_type'] = "success"; 
    } else {
        $_SESSION['toast_message'] = "Error updating email: " . $conn->error;
        $_SESSION['toast_type'] = "error"; 
    }

    $stmt->close();
    $conn->close();
    header("Location: settings.php");
    exit();
}
?>
