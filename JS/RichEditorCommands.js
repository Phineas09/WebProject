window.onload = () => {formatiFrames();}

window.onmessage = iFramePipeReceive;

var activeiFrame = null;

function iFramePipeReceive(event) {

    event.data.hasOwnProperty("frameHeight")&&
    (document.getElementById(event.data.frameOrigin).style.height=
    `${event.data.frameHeight+30}px`);

    if(event.data.hasOwnProperty("frameElement")) {
        var iFrame = document.getElementById(event.data.frameElement);

        if(activeiFrame) {
            closeiFrame();
        }
        activeiFrame = iFrame;
        
        window.ondblclick = closeiFrame;

        activateTextEditor(iFrame);
        iFrame.contentWindow.window.postMessage({
            active: true
        }, '*');
    }
}

function closeiFrame() {

    window.removeEventListener("ondblclick", closeiFrame);
    closeTextEditor(activeiFrame);
    activeiFrame.contentWindow.window.postMessage({
        inactive: true
    }, '*');
    activeiFrame = null;
}

/*
window.onload = function() {
    var iframes = document.getElementsByTagName('iframe');
    for (var i = 0, len = iframes.length, doc; i < len; ++i) {
        doc = iframes[i].contentDocument || iframes[i].contentWindow.document;
        //doc = iframes[i].contentDocument;
        console.log(doc);
        doc.designMode = "on";
    }
};
*/

var defaultTextShow = '<div style="text-align: center;">Enter Text Here</div>';

function formatiFrames() {
    var iframes = document.getElementsByTagName('iframe');
    for(let iframe of iframes) {
        if(!iframe.getAttribute("formatted") && iframe.name === "richTextField"){ 
            iframe.setAttribute("formatted", "true");

            iframe.contentDocument.getElementsByTagName("body")[0].innerHTML = defaultTextShow;

            var script   = document.createElement("script");
            script.type  = "text/javascript";
            script.src   = "./JS/iFrame.js";    // use this for linked script
            iframe.contentDocument.head.appendChild(script);

            var cssLink = document.createElement("link");
            cssLink.href = "./../Css/iFrame.css"; 
            cssLink.rel = "stylesheet"; 
            cssLink.type = "text/css"; 
            iframe.contentDocument.head.appendChild(cssLink);
        }
    }
}

function activateTextEditor(element) {
    element.contentDocument.designMode = "on";
    element.classList.add("selectediFrame");
    element.parentElement.childNodes[1].classList.remove("hiddenEditorOptions");
}

function closeTextEditor(element) {
    element.contentDocument.designMode = "off";
    element.classList.remove("selectediFrame");
    element.parentElement.childNodes[1].classList.add("hiddenEditorOptions");
}

// ! Get iframe contents 
//console.log(textField.contentDocument.getElementsByTagName("body")[0].innerHTML);

function execCmd (command, element) {
    var textField = element.parentElement.parentElement.childNodes[3];
    textField.contentDocument.execCommand(command, false, null);
}

function execCmdToggleCode(element) {

    var textField = element.parentElement.parentElement.childNodes[3];

    //! This does not work fore some reason, therefore i removed the margin of code styled div
    textField.contentDocument.execCommand("insertBrOnReturn", false, null);

    var parentNode = textField.contentWindow.getSelection().focusNode.parentNode;
    if(parentNode.tagName == "CODE"){
        parentNode.parentElement.classList.remove("codebox");
        execCmd("removeFormat", element);
        return;
    }
    textField.contentDocument.execCommand("insertHTML", false, '<div class="codebox"> <code>' + textField.contentWindow.getSelection()
    .toString().replace(/\n/g, '<br>'));

}

function execCommandWithArg(command, arg, element) {
    var textField = element.parentElement.parentElement.childNodes[3];
    if(command == "foreColor") {
        arg = arg.substr(1);
    }

    textField.contentDocument.execCommand(command, false, arg);
}

function showSource(element) {
    var textField = element.parentElement.childNodes[3];
    //document.getElementById("textEditor").innerHTML = textField.contentDocument.getElementsByTagName("body")[0].innerHTML;
    console.log(textField.contentDocument.getElementsByTagName("body")[0].innerHTML);

}

