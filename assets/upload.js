document.getElementById('image-input').addEventListener('change', function(){
    let formData = new FormData();
    console.log(formData);
    formData.append('file', this.files[0]);
    console.log(formData);

    fetch('/upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.url) {
            let markdown = `![alt text](${data.url})`;
            let textarea = document.getElementById('post-body');
            textarea.value += markdown;
            // Update preview
            const preview = document.getElementById('post-preview');
            const markdownText = textarea.value;
            preview.innerHTML = marked.parse(markdownText); // Convert markdown to HTML and update the preview
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});
