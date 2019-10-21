$('#post_imageFile_file').on('change', function () {
    let fullPath = $('#post_imageFile_file').val();
    let fileName = fullPath.split(/(\\|\/)/g).pop();

    $('.custom-file-label').text(fileName);

    $('#post_message').removeAttr('required');
})

function getQuoteText() {
    if (window.getSelection().toString() === "") {
        return false;
    }

    var isSameNode = window.getSelection().anchorNode.isSameNode(window.getSelection().focusNode);

    if (isSameNode === false) {
        return false;
    }

    // Possibly partial message
    if (typeof window.getSelection().focusNode.classList === "undefined") {
        // Check if parent is a message <p>
        if (window.getSelection().focusNode.parentElement.classList.contains('message') === false) {
            return false;
        }
    } else if (window.getSelection().focusNode.classList.contains('message') === false) {
        return false;
    }

    var quote = "";
    
    if (window.getSelection) {
        quote = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        quote = document.selection.createRange().text;
    }

    return quote;
}

$(".delete_button").click(function(event) {
    this.parentElement.submit();
});