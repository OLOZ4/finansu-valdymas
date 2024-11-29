<?php
session_start();
require_once("config.php");

// Check for connection errors
if ($conn->connect_error) {
    die('Connect Error: ' . $conn->connect_error);
}

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log messages to the console
function log_message($message) {
    echo "[LOG] " . date('Y-m-d H:i:s') . ": " . $message . "\n";
}

// Get the expense data from the form and some global sesion variables
$amount = $_POST['amount'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// Define the update expenses function
function update_income($conn, $user_id, $amount, ) {
    
    $stmt = $conn->prepare('UPDATE income SET amount = amount + ? WHERE user_id = ?');
    
    // Bind parameters
    $stmt->bind_param('is', $amount, $user_id);
    
    // Execute query
    if (!$stmt->execute()) {
        log_message("Error executing query: " . $stmt->error);
        die('Query failed: ' . $stmt->error);
    }
}

// Execute function


update_income($conn, $user_id, $amount);

?>