var body = document.body,
    html = document.documentElement;

MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

var observer = new MutationObserver(function(mutations, observer) {
    // fired when a mutation occurs
    //console.log(mutations, observer);
    //console.log("here");
    sendPipeMessage();
});  

observer.observe(body, {
    subtree: true,
    attributes: true,
    childList: true
});

function getHeight() {
    return Math.min( body.scrollHeight, body.offsetHeight, 
    html.clientHeight, html.scrollHeight, html.offsetHeight);
}

var height = getHeight();

function sendPipeMessage()  {
    var height = getHeight();
    if(height <= 150) {
        height = 150;
    }
    window.parent.postMessage({
    frameHeight: height,
    frameOrigin : window.frameElement.id
    }, '*');
}

window.onmessage = (event) => {
    event.data.hasOwnProperty("active")&&(window.ondblclick = null);
    event.data.hasOwnProperty("inactive")&&(window.ondblclick = postiFrameId);
}

window.ondblclick = postiFrameId;
/*
window.onclick = (event) => {
    console.log("aa");
    window.parent.postMessage({
        frameFocus: window.frameElement.id
        }, '*');

    }
*/
function postiFrameId () {
    window.parent.postMessage({
        frameElement: window.frameElement.id
    }, '*');
}