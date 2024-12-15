// JavaScript to load the correct profile image when the page is loaded
window.addEventListener('DOMContentLoaded', (event) => {
    const userId = document.getElementById('userId').value; // Get the user ID from the hidden input
    const profilePic = document.querySelector('.profile-pic img'); // Get the profile image element

    // Fetch the user's profile image
    fetch('getProfileImage.php', {
        method: 'GET',
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Set the profile image source to the retrieved image
                profilePic.src = data.image + '?t=' + Date.now(); // Adding cache-busting query param
            } else {
                console.error('Error fetching image:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching image:', error);
        });
});
