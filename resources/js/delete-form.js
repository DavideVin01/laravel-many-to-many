const deleteForms = document.querySelectorAll('.delete-form');
deleteForms.forEach(form => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const accept = confirm(`Are you sure you want to delete this post?`);
        if (accept) e.target.submit();
    });
})