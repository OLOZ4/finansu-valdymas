const dragDropArea = document.querySelector('.drag-drop-area');
const dragDropAreaImg = document.querySelector('.drag-drop-area-img'); // Reference to the image preview
const fileInput = document.querySelector('.drag-drop-area input[type="file"]');
const userId = document.getElementById('userId').value; // Assuming there's a hidden field for userId

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 100);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Drag-and-drop handlers
dragDropArea.addEventListener('dragover', (event) => {
    event.preventDefault();
    dragDropArea.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
});

dragDropArea.addEventListener('dragleave', () => {
    dragDropArea.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
});

dragDropArea.addEventListener('drop', (event) => {
    event.preventDefault();
    dragDropArea.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';

    const file = event.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        displayImage(file);
    } else {
        showToast('Please drop a valid image file.', 'error');
    }
});

// File input change handler
function handleFileChange(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        displayImage(file);
    } else {
        showToast('Please select a valid image file.', 'error');
    }
}

// Function to display the image and upload it
function displayImage(file) {
    const reader = new FileReader();

    reader.onload = (e) => {
        // Show the image in the drag-drop area
        dragDropAreaImg.src = e.target.result;  // Set the image source to the selected file
        dragDropAreaImg.style.display = 'block'; // Ensure the image is displayed
        uploadImage(file, userId); // Call upload function after preview
    };

    reader.readAsDataURL(file);
}

// Function to upload image to the server
function uploadImage(file, userId) {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('id', userId);

    fetch('seUploadImage.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Get the profile picture element in the navbar
                const navbarProfileImg = document.querySelector('.navbar-profile img');
                // Update the navbar profile image src with the uploaded image path
                navbarProfileImg.src = `profile pictures/${userId}.${data.extension}?t=${Date.now()}`;

                // Also update the drag-drop area image (as you already did)
                dragDropAreaImg.src = `profile pictures/${userId}.${data.extension}?t=${Date.now()}`;
                 showToast('Image uploaded successfully. Please refresh the page', 'success');
            } else {
                alert(data.message, 'error'); // Show error if image upload failed
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while uploading the image.', 'error');
        });
}


// Reset button functionality
function resetForm() {
    const userId = document.getElementById('userId').value; // Retrieve user ID

    if (!userId) {
        alert('User ID is missing.');
        return;
    }

    // Reset the drag-and-drop area back to its initial state
    dragDropAreaImg.style.display = 'none'; // Hide the image preview
    dragDropAreaImg.src = 'profile pictures/test.jpg'; // Show the default test image

    // Call the server to delete the user's image
    fetch('seDeleteImage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: userId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
               showToast('An error occurred while uploading the image.', 'error');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
             showToast('An error occurred while uploading the image.', 'error');
        });
}
