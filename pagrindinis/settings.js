document.querySelectorAll('.close-btn').forEach(button => {
    button.addEventListener('click', () => {
        button.parentElement.parentElement.classList.remove('visible');
    });
});