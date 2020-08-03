Array.from(document.getElementsByClassName("btn-collapse")).forEach(function(button) {
    button.onclick = collapse;
});

function collapse(event) {
    let postEl = document.querySelector(this.dataset.target);
    let postCount = countChildren(postEl);

    if (postEl.classList.contains('collapsed')) {
        setButtonText(this.children[0], '−');
    } else {
        setButtonText(this.children[0], `[${postCount}]`);
    }

    postEl.classList.toggle('collapsed');
}

function countChildren(element) {
    return element.childElementCount;
}

function setButtonText(element, text) {
    element.innerText = text;
}