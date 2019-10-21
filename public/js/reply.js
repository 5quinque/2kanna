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