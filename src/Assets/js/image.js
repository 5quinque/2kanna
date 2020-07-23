const conColumn = "col-20";
const expandedColumn = "col-100";

Array.from(document.getElementsByClassName("post-image")).forEach(function(item) {
    item.onclick = imageClick;
});

function imageClick(event) {
    event.preventDefault();

    let column = this.parentElement.parentElement;

    if (column.classList.contains(conColumn)) {
        // Load the full size image
        this.src = this.parentElement.href;
    }

    column.classList.toggle(expandedColumn);
    column.classList.toggle(conColumn);
} 