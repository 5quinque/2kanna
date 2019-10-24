
function getQuoteText() {
    if (window.getSelection().toString() === "") {
        return false;
    }

    // if (isValidQuote() === false) {
    //     return false;
    // }

    var quote = "";
    
    if (window.getSelection) {
        quote = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        quote = document.selection.createRange().text;
    }

    return quote;
}

function isValidQuote() {
    var isSameNode = window.getSelection().anchorNode.isSameNode(window.getSelection().focusNode);

    if (isSameNode === false) {
        console.log("not same node", window.getSelection())
        return false;
    }

    // Possibly partial message
    if (typeof window.getSelection().focusNode.classList === "undefined") {
        // Check if parent is a message <p>
        if (window.getSelection().focusNode.parentElement.classList.contains('message') === false) {
            console.log("parent not message", window.getSelection())
            return false;
        }
    } else if (window.getSelection().focusNode.classList.contains('message') === false) {
        console.log("full not message", window.getSelection());
        return false;
    }

    return true;
}

$(".reply_button").click(function(event) {
    event.preventDefault();
    
    var id;
    var quote;
    var messageElement = $("#post_message");
    var oldId;

    this.classList.forEach(function(a_class) {
        // console.log(a_class);
        id = a_class.match(/^post_(\d+)$/);
        if (id) {
            id = id[1]
            console.log(id);
            // Set parent post id on reply form
            // todo
            oldId = $("#post_parent_post")[0].selectedIndex;
            
            $(`#post_parent_post option[value=${oldId}]`).attr('selected', false);
            $(`#post_parent_post option[value=${id}]`).attr('selected', true);

            // Retrieve any quote text and place in reply form message textarea
            // [todo] check if selected text is in message that user is intending to reply to ?
            quote = getQuoteText();
            if (quote !== false) {
                messageElement.val(`${messageElement.val()}>${quote}\n`);
            }

            return true;
        }
    });
});