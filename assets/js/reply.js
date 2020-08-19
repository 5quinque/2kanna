
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

function replyClick(event) {
    event.preventDefault();
    
    let id;
    let quote;
    let messageElement = document.getElementById("post_message");

    removeHighlight();

    this.parentElement.parentElement.parentElement.className += " post-reply";

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

function removeHighlight() {
    Array.from(document.getElementsByClassName("post-reply")).forEach(function(item) {
        item.classList.remove("post-reply");
    });
}

export function highlightReply() {
    removeHighlight();
    
    let id = document.getElementById("post_parent_post").value;
    let post;

    Array.from(document.getElementsByClassName(`post_${id}`)).forEach(function(item) {
        post = item.parentElement.parentElement.parentElement;
        if (post.classList.contains('post-reply') === false) {
            post.className += " post-reply";
        }
    });
}

export function setReplyOnclick() {
    Array.from(document.getElementsByClassName("reply_button")).forEach(function(item) {
        if (item.onclick === null) {
            item.onclick = replyClick;
        }
    });
}

/* Delete button */

function submitDeleteForm() {
    this.parentElement.submit();
}

function submitStickyForm() {
    this.parentElement.submit();
}

export function setDeleteOnclick() {
    Array.from(document.getElementsByClassName("delete_button")).forEach(function(item) {
        if (item.onclick === null) {
            item.onclick = submitDeleteForm;
        }
    });
}

export function setStickyOnclick() {
    Array.from(document.getElementsByClassName("sticky_button")).forEach(function(item) {
        if (item.onclick === null) {
            console.log('test');
            item.onclick = submitStickyForm;
        }
    });
}