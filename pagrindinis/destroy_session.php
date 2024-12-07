<?php
  header('Cache-Control: no-cache, must-revalidate');
  session_start(); // Start the session
  unset($_SESSION['logged_in']);
  $_SESSION['logged_in'] = false;
  session_destroy(); // Stop the session
  header('Location: index.php'); // Redirect user
  exit; // Quit script
?>