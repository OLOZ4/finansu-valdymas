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


// Get the expense data from the form
$amount = $_POST['amount'];
$category = $_POST['category'];
$description = $_POST['description'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];



// Define the update expenses function
function update_expenses($conn, $user_id, $amount, $category, $description) {
    
    //log_message($user_id);

    // Prepare query
    $stmt = $conn->prepare('INSERT INTO expenses (user_id, amount, category, description) VALUES (?, ?, ?, ?)');
    
    // Bind parameters
    $stmt->bind_param('idss', $user_id, $amount, $category, $description);
    
    // Execute query
    if (!$stmt->execute()) {
        log_message("Error executing query: " . $stmt->error);
        die('Query failed: ' . $stmt->error);
    }

    //////////////////////////////////


}
update_expenses($conn, $user_id, $amount, $category, $description);

?>