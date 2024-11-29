<?php
  session_start(); // Start the session
  session_destroy(); // Stop the session
  header('Location: index.html'); // Redirect user
  exit; // Quit script
?>