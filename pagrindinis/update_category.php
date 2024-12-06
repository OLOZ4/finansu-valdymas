<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $value = (float) $_POST['value'];
    $user_id = $_SESSION['user_id'];

    try {
        // Prepare the SQL statement with a parameterized query
        $stmt = $conn->prepare("SELECT SUM(amount) AS total_value FROM expenses WHERE user_id = ? AND category = ?");
        $stmt->bind_param('is', $user_id, $category);

        // Execute the prepared query
        $stmt->execute();

        // Get the result set as an associative array
        $result = $stmt->get_result();
        $totalValue = $result->fetch_assoc()['total_value'];
        $new_value  = number_format($totalValue, 2); // Use the total value from the database

        // Create an array to store the response data
        $data = array(
            'category' => $category,
            'value' => $new_value
        );

        echo json_encode($data);

    } catch (Exception $e) {
        // Handle any exceptions that occur during execution
        http_response_code(500);
        echo '{"error": "' . $e->getMessage() . '"}';
    }
}
?>