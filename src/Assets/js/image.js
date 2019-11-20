Array.from(document.getElementsByClassName("post-image")).forEach(function(item) {
    item.onclick = imageClick;
});

function imageClick(event) {
    event.preventDefault();

    let column = this.parentElement.parentElement;

    if (column.classList.contains('col-3')) {
        this.src = this.src.replace(/t\/thumb\//, '');

        column.classList.add('col-12');
        column.classList.remove('col-3');
    } else {
        column.classList.add('col-3');
        column.classList.remove('col-12');
    }
} 