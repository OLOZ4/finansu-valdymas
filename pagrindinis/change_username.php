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
        $_SESSION['toast_message'] = "Username updated successfully!";
        $_SESSION['toast_type'] = "success"; // 'success' or 'error'
    } else {
        $_SESSION['toast_message'] = "Error updating username: " . $conn->error;
        $_SESSION['toast_type'] = "error"; // 'success' or 'error'
    }

    $stmt->close();
    $conn->close();

    header("Location: settings.php");
    exit();
}
?>
