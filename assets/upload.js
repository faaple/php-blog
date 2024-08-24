document.getElementById('image-input').addEventListener('change', function(){
    let formData = new FormData();
    console.log(formData);
    formData.append('file', this.files[0]);
    console.log(formData);

    fetch('/php-blog/upload.php', {
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
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});