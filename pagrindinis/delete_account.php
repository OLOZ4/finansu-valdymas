<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $confirm_delete = $_POST['confirm-delete'];
    $user_id = $_SESSION['user_id'];

    if ($confirm_delete === "DELETE") {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Delete related records in the income table
            $stmt = $conn->prepare("DELETE FROM income WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Delete related records in other tables if necessary
            $stmt = $conn->prepare("DELETE FROM expenses WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Delete the user
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();

            session_destroy();
            echo "Account deleted successfully!";
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "index.php";
                    }, 2000);
                  </script>';
        } catch (mysqli_sql_exception $exception) {
            // Rollback the transaction if an error occurs
            $conn->rollback();
            throw $exception;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Confirmation text does not match.";
    }
}
?>