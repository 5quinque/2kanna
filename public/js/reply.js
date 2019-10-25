
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

var parentId;

$(".reply_button").click(function(event) {
    event.preventDefault();
    
    var id;
    var quote;
    var messageElement = $("#post_message");

    this.classList.forEach(function(a_class) {
        // console.log(a_class);
        id = a_class.match(/^post_(\d+)$/);
        if (id) {
            id = id[1]
            console.log(id);
            parentId = id;

            // Set parent post id on reply form
            // todo
            
            $(`#post_parent_post option[value=${parentId}]`).attr('selected', false);
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