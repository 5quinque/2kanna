$('#post_imageFile_file').on('change', function () {
    let fullPath = $('#post_imageFile_file').val();
    let fileName = fullPath.split(/(\\|\/)/g).pop();

    $('.custom-file-label').text(fileName);

    $('#post_message').removeAttr('required');
})

$(".delete_button").click(function(event) {
    this.parentElement.submit();
});