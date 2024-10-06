<?php
  // Database connection settings
  $host = 'mysql';
  $username = 'test';
  $password = 'test';
  $database = 'db';

  // Create a new MySQL connection
  $conn = new mysqli($host, $username, $password, $database);

  // Check for connection errors
  if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
  }
?>