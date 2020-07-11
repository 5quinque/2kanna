Array.from(document.getElementsByClassName("post-image")).forEach(function(item) {
    item.onclick = imageClick;
});

function imageClick(event) {
    event.preventDefault();

    let column = this.parentElement.parentElement;

    if (column.classList.contains('col-20')) {
        let fullImagePath = this.parentElement.pathname;
        
        this.src = fullImagePath;

        column.classList.add('col-100');
        column.classList.remove('col-20');
    } else {
        column.classList.add('col-20');
        column.classList.remove('col-100');
    }
} 