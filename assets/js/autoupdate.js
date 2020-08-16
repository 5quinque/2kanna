
(function() {
var latestJsonTree = [];
var rootSlug;
var running = false;

function getSlug() {
    rootSlug = document.querySelector('.post-container').id;
}

function ajaxGetTree() {
    running = true;
    httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = handleJson;
    httpRequest.open('GET', `/json/tree/${rootSlug}`);
    httpRequest.send();
}

function handleJson() {
    if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
        latestJsonTree = JSON.parse(httpRequest.responseText);

        traverseTree(rootSlug, latestJsonTree);
    }

    running = false;
}

function ajaxGetPost(parent, slug) {
    httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
            //console.log(httpRequest.responseText);
            document.getElementById(parent).innerHTML += httpRequest.responseText;

            window.newReplies = true;
            // document.querySelectorAll(".post-fresh .reply_button").forEach(function(item) {
                // item.onclick = replyClick;
            // });
        }
    };
    httpRequest.open('GET', `/i/miscellaneous/${slug}`);
    httpRequest.send();
}

function traverseTree(parent, object) {
    //console.log(object);
    for (const property in object) {
        //console.log(`Property: ${property}: ${object[property]}`);

        if (document.getElementById(property) === null) {
            // Post doesn't exist in DOM
            ajaxGetPost(parent, property);
        }

        for (const i in object[property]) {
            // console.log(object[property][i]);
            traverseTree(property, object[property][i]);
        }
    }
}

function updatePosts() {
    if (!running) {
        ajaxGetTree();
    }

    // console.log(jsonTree, latestJsonTree);
}

getSlug();
//updatePosts();
setInterval(updatePosts, 6000);


})();