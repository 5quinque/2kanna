import * as Collapse from './collapse';
import * as Reply from './reply';
import * as Image from './image';

var latestJsonTree = [];
var rootSlug;
var running = false;

function getSlug() {
    rootSlug = document.querySelector('.post-container').id;
}

function ajaxGetTree() {
    running = true;

    let httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
            latestJsonTree = JSON.parse(httpRequest.responseText);
    
            traverseTree(rootSlug, latestJsonTree);
        }
    
        running = false;
    };

    httpRequest.open('GET', `/json/tree/${rootSlug}`);
    httpRequest.send();
}

function ajaxGetPost(parent, slug) {
    let httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
            document.getElementById(parent).innerHTML += httpRequest.responseText;

            Reply.setReplyOnclick();
            Reply.setDeleteOnclick();
            Collapse.setCollapseOnclick();
            Image.setImageOnclick();
        }
    };
    httpRequest.open('GET', `/i/miscellaneous/${slug}`);
    httpRequest.send();
}

function traverseTree(parent, object) {
    for (const property in object) {
        if (document.getElementById(property) === null) {
            // Post doesn't exist in DOM
            ajaxGetPost(parent, property);
        }

        for (const i in object[property]) {
            traverseTree(property, object[property][i]);
        }
    }
}

export function updatePosts() {
    if (typeof rootSlug === 'undefined') {
        getSlug();
    }

    if (!running) {
        ajaxGetTree();
    }
}
