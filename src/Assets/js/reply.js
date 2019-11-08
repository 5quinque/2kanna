
function getQuoteText() {
    if (window.getSelection().toString() === "") {
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

Array.from(document.getElementsByClassName("reply_button")).forEach(function(item) {
    item.onclick = replyClick;
});

function replyClick(event) {
    event.preventDefault();
    
    let id;
    let quote;
    let messageElement = document.getElementById("post_message");

    Array.from(document.getElementsByClassName("post-reply")).forEach(function(item) {
        item.classList.remove("post-reply");
    });

    this.parentElement.parentElement.className += " post-reply";

    this.classList.forEach(function(a_class) {
        id = a_class.match(/^post_(\d+)$/);
        if (id) {
            id = id[1]

            document.getElementById("post_parent_post").value = id;

            // Retrieve any quote text and place in reply form message textarea
            quote = getQuoteText();
            if (quote !== false) {
                messageElement.value = `${messageElement.value}>${quote}\n`;
            }

            return true;
        }
    });
}
