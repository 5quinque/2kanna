
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

$(".reply_button").click(function(event) {
    event.preventDefault();
    
    var id;
    var quote;
    var messageElement = $("#post_message");

    console.log(this.parentElement.parentElement);

    this.classList.forEach(function(a_class) {
        id = a_class.match(/^post_(\d+)$/);
        if (id) {
            id = id[1]
            console.log(id);
            parentId = id;

            $("#post_parent_post").val(id);

            // Retrieve any quote text and place in reply form message textarea
            quote = getQuoteText();
            if (quote !== false) {
                messageElement.val(`${messageElement.val()}>${quote}\n`);
            }

            return true;
        }
    });
});