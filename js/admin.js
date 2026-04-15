document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.edit-btn');
    const editForm = document.getElementById('edit-form');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            // read the sticky notes from the button
            document.getElementById('edit-id').value = button.dataset.id;
            document.getElementById('edit-name').value = button.dataset.name;
            document.getElementById('edit-description').value = button.dataset.description;
            document.getElementById('edit-price').value = button.dataset.price;
            document.getElementById('edit-category').value = button.dataset.category;
            document.getElementById('edit-image').value = button.dataset.image;

            // show the edit form
            editForm.style.display = 'block';

            // scroll down to the edit form
            editForm.scrollIntoView({ behavior: 'smooth' });
        });
    });
});