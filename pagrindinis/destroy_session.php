<?php
  session_start(); // Start the session
  session_destroy(); // Stop the session
  header('Location: login.php'); // Redirect user
  exit; // Quit script
?>