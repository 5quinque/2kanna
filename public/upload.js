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

$(".reply_button").click(function(event) {
    event.preventDefault();
    
    var id;
    var quote;

    this.classList.forEach(function(a_class) {
        // console.log(a_class);
        id = a_class.match(/^post_(\d+)$/);
        if (id) {
            id = id[1]
            console.log(id);
            // Set parent post id on reply form
            $(`#post_parent_post option[value=${id}]`).attr('selected', true);

            // Retrieve any quote text and place in reply form message textarea
            // [todo] check if selected text is in message that user is intending to reply to ?
            quote = getQuoteText();
            if (quote !== false) {
                $("#post_message").append(`>${quote}`);
            }

            return true;
        }
    });
});

$(".delete_button").click(function(event) {
    this.parentElement.submit();
});