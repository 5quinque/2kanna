let imageFile = document.getElementById("post_imageFile_file");

imageFile.onchange = updateImageFileText;

Array.from(document.getElementsByClassName("delete_button")).forEach(function(item) {
    item.onclick = submitDeleteForm;
});

function updateImageFileText() {
    let fullPath = imageFile.value;
    let fileName = fullPath.split(/(\\|\/)/g).pop();

    Array.from(document.getElementsByClassName("custom-file-label")).forEach(function(item) {
        item.textContent = fileName;
    });

    document.getElementById("post_message").removeAttribute("required"); 
}

function submitDeleteForm() {
    this.parentElement.submit();
}
