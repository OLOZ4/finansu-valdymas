<?php
session_start(); // Start the session to access user data

// Check if user ID exists in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in.',
    ]);
    exit;
}

// Retrieve the user ID from the session
$userId = intval($_SESSION['user_id']); // Ensure it's an integer for security

// Check if the request is POST and contains a file
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['image'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Please upload an image.',
    ]);
    exit;
}

// Get file info
$file = $_FILES['image'];
$targetDir = 'profile pictures/';
$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

// Validate file type
if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.',
    ]);
    exit;
}

// Check if the user already has an image
$newFileName = $userId . '.' . $fileExtension;
$targetPath = $targetDir . $newFileName;

// If the user already has an image, check and delete the old one
if (file_exists($targetPath)) {
    unlink($targetPath); // Delete the old image
}

// Move the uploaded file to the target directory
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Image uploaded successfully.',
        'extension' => $fileExtension,
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to upload the image.',
    ]);
}
