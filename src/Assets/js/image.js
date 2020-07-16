const conColumn = "col-20";
const expandedColumn = "col-100";

Array.from(document.getElementsByClassName("post-image")).forEach(function(item) {
    item.onclick = imageClick;
});

function imageClick(event) {
    event.preventDefault();

    let column = this.parentElement.parentElement;

    if (column.classList.contains(conColumn)) {
        // Expand the image
        let fullImagePath = this.parentElement.href;
        
        this.src = fullImagePath;

        column.classList.add(expandedColumn);
        column.classList.remove(conColumn);
    } else {
        // Contract the image
        column.classList.add(conColumn);
        column.classList.remove(expandedColumn);
    }
} 