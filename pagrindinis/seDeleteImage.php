<?php
session_start();

// Ensure the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit;
}

$userId = intval($_SESSION['user_id']);
$imageDir = 'profile pictures/'; // Path to your profile pictures folder
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed extensions

// Attempt to delete the user's image
$deleted = false;
foreach ($imageExtensions as $ext) {
    $filePath = $imageDir . $userId . '.' . $ext;
    if (file_exists($filePath)) {
        unlink($filePath);
        $deleted = true;
        break;
    }
}

if ($deleted) {
    echo json_encode(['status' => 'success', 'message' => 'Image deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No image found to delete.']);
}
exit;
?>