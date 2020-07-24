const conColumn = "col-20";
const expandedColumn = "col-100";

Array.from(document.getElementsByClassName("post-image")).forEach(function(item) {
    item.onclick = imageClick;
});

Array.from(document.getElementsByClassName("post-video")).forEach(function(item) {
    item.onplay = videoClick;
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

function videoClick(event) {
    let column = this.parentElement;
    
    if (column.classList.contains(expandedColumn)) {
        return;
    }

    column.classList.toggle(expandedColumn);
    column.classList.toggle(conColumn);
}