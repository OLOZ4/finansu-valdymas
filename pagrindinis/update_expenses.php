<?php
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


// Define the update expenses function
function update_expenses($conn, $amount, $category) {
    // Prepare query
    $stmt = $conn->prepare('INSERT INTO expenses (amount, category) VALUES (?, ?)');
    
    // Bind parameters
    $stmt->bind_param('ii', $amount, $category);
    
    // Execute query
    if (!$stmt->execute()) {
        log_message("Error executing query: " . $stmt->error);
        die('Query failed: ' . $stmt->error);
    }
}

// Test the function
update_expenses($conn, $amount, $category);

?>