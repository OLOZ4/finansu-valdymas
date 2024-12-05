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

$user_id = $_SESSION['user_id'];
$income_sum = 0;
$expense_sum = 0;

$stmt = $conn->prepare('SELECT SUM(amount) FROM income  WHERE user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $income_sum += (double) $row['SUM(amount)'];
        }
}

$stmt = $conn->prepare('SELECT SUM(amount) FROM expenses  WHERE user_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $expense_sum += (double) $row['SUM(amount)'];
        }
    }

$data = array(
    'expense_sum' => $expense_sum,
    'income_sum' => $income_sum
);

header('Content-Type: application/json');
ob_start();
echo json_encode($data);
ob_end_flush();

    //var_dump($income_sum);


?>