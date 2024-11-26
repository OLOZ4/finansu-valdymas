// update_expenses.php

<?php


// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the expense data from the form
$amount = $_POST['amount'];
$category = $_POST['category'];

// Update the expenses table
/*
$query = "UPDATE expenses SET amount = ?, category = ? WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("iii", $amount, $category, $id);
$stmt->execute();
*/

$query = $conn->query("INSERT INTO test (amount,category) VALUES ('$amount, '$category')");

$query = $conn->prepare("INSERT INTO test (amount, category) VALUES (:amount, :category)");
$query->bind_param(":amount", $amount);
$query->bind_param(":category", $category);
try {
    $query->execute();
} catch (Exception $e) {
    echo "Error inserting data: " . $e->getMessage();
}

// Close the database connection
$mysqli->close();



/*
    // code for home.js
 try {
        fetch("update_expenses.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `amount=${amount1}&category=${category1}`
        })
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.error(error));
    } catch (error) {
        console.error("Error submitting expense:", error);
    }





*/
?>