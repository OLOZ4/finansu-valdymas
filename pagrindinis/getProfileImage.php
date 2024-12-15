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

// Function to check if the user has a profile image
function getProfileImage($userId) {
    $targetDir = 'profile pictures/';
    
    // Check for the existence of different image formats
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    foreach ($imageExtensions as $extension) {
        $imagePath = $targetDir . $userId . '.' . $extension;
        if (file_exists($imagePath)) {
            return $imagePath; // Return the first image that exists
        }
    }

    // If no image exists, return the default image
    return $targetDir . 'test.jpg'; // Default image
}

// Call the function and return the image path
$imagePath = getProfileImage($userId);

// Return the image path in JSON format
echo json_encode([
    'status' => 'success',
    'image' => $imagePath,
]);
?>
