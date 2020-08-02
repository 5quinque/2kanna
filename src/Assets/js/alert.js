Array.from(document.getElementsByClassName("alert-close")).forEach(function(button) {
    button.onclick = close;
});

function close(event) {
    let element = findAlertEl(this);

    element.classList.add('fade-out');

    setTimeout(function() {
        element.remove();
    }, 1000);
}

function findAlertEl(element) {
    if (element.parentElement === null) {
        return null;
    }

    if (element.parentElement.classList.contains('alert')) {
        return element.parentElement;
    }

    return findAlertEl(element.parentElement);
}