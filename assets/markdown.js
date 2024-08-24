document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('post-body');
    const preview = document.getElementById('post-preview');

    // Function to update the preview
    function updatePreview() {
        const markdownText = textarea.value;
        preview.innerHTML = marked.parse(markdownText); // Convert markdown to HTML and update the preview
    }

    // Event listener for textarea input
    textarea.addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();
});
