window.onload = () => {formatEditorElements();}

window.onmessage = iFramePipeReceive;

var activeiFrame = null;
var activeDraw = null;

function iFramePipeReceive(event) {

    event.data.hasOwnProperty("frameHeight")&&
    (document.getElementById(event.data.frameOrigin).style.height=
    `${event.data.frameHeight+30}px`);

    if(event.data.hasOwnProperty("frameElement")) {
        openiFrame(event.data.frameElement);
    }

    if(event.data.hasOwnProperty("canvasElement")) {
        enableDraw(document.getElementById(event.data.canvasElement));
    }
}

function openiFrame(frameElement) {
    var iFrame = document.getElementById(frameElement);

    if(activeiFrame) {
        closeiFrame();
    }
    
    if(activeDraw) {
        activeDraw.paint.unBind();
    }

    activeiFrame = iFrame;
    
    window.ondblclick = closeActiveElement;

    activateTextEditor(iFrame);
    iFrame.contentWindow.window.postMessage({
        active: true
    }, '*');
}

function closeActiveElement(event) {
    //Close any active elements
    if(activeDraw) {
        if (activeDraw !== event.target) {  
            //If the dbclick was made outside a drawing close id  
            activeDraw.paint.unBind();
        }
    }
    //if dbclick was made outside iframe close it (clicks inside iframe are captured by the iFrame.js script inside each iframe)
    if(activeiFrame) {
        closeiFrame();
    }
}

function deleteiFrame(frameElement) {
    var editorContainer = frameElement.parentElement;
    var editorAddCell = editorContainer.nextElementSibling;
    var content = editorContainer.parentElement;
    content.removeChild(editorContainer);
    content.removeChild(editorAddCell);
}

function closeiFrame() {
    window.removeEventListener("ondblclick", closeActiveElement);
    closeTextEditor(activeiFrame);
    activeiFrame.contentWindow.window.postMessage({
        inactive: true
    }, '*');
    activeiFrame = null;
}

function formatiFrames() {
    var iframes = document.getElementsByTagName('iframe');
    for(let iframe of iframes) {
        if(!iframe.getAttribute("formatted") && iframe.name === "richTextField"){ 

            iframe.contentDocument.getElementsByTagName("body")[0].innerHTML = iframe.id +   '<div style="text-align: center;">Enter Text Here</div>';

            var script   = document.createElement("script");
            script.type  = "text/javascript";
            script.src   = "/JS/iFrame.js";   
            iframe.contentDocument.head.appendChild(script);

            var cssLink = document.createElement("link");
            cssLink.href = "/Css/iFrame.css"; 
            cssLink.rel = "stylesheet"; 
            cssLink.type = "text/css"; 
            iframe.contentDocument.head.appendChild(cssLink);
            iframe.setAttribute("formatted", "true");

        }
    }
}

function formatEditorElements() {

    formatDrawElements();
    formatiFrames();
}

function activateTextEditor(element) {
    element.contentDocument.designMode = "on";
    element.classList.add("selectediFrame");
    element.previousElementSibling.classList.remove("hiddenEditorOptions");
    element.nextElementSibling.classList.add("hiddenEditorOptions");
}

function closeTextEditor(element) {
    element.contentDocument.designMode = "off";
    element.classList.remove("selectediFrame");
    element.previousElementSibling.classList.add("hiddenEditorOptions");
    element.nextElementSibling.classList.remove("hiddenEditorOptions");
}

// ! Get iframe contents 
//console.log(textField.contentDocument.getElementsByTagName("body")[0].innerHTML);

function execCmd (command, element) {
    var textField = element.parentElement.nextElementSibling;
    textField.contentDocument.execCommand(command, false, null);
}

function execCmdToggleCode(element) {

    var textField = element.parentElement.nextElementSibling;

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
    var textField = element.parentElement.nextElementSibling;
    if(command == "foreColor") {
        arg = arg.substr(1);
    }

    textField.contentDocument.execCommand(command, false, arg);
}

function showSource(element) {
    var textField = element.nextElementSibling;
    //document.getElementById("textEditor").innerHTML = textField.contentDocument.getElementsByTagName("body")[0].innerHTML;
    console.log(textField.contentDocument.getElementsByTagName("body")[0].innerHTML);

}

//! More buttons

var visible = false;
var currentButton = null;

function showMenu(button) {
    if (!visible) {
        currentButton = button;
        visible = true;
        button.parentElement.classList.add('show-more-menu');
        button.nextElementSibling.setAttribute('aria-hidden', false);
        document.addEventListener('mousedown', hideMenu, false);
    }
}

function hideMenu(e) {
    if (currentButton && currentButton.parentElement.contains(e.target)) {
        return;
    }
    if (visible) {
        visible = false;
        currentButton.parentElement.classList.remove('show-more-menu');
        currentButton.nextElementSibling.setAttribute('aria-hidden', true);
        document.removeEventListener('mousedown', hideMenu);
        currentButton = null;
    }
}

//! Render functions part !@#!@

function renderElementAddToDom(parentElement, elementClass, innerHTML) {

    var element = document.createElement("div");
    element.classList.add(elementClass);
    element.innerHTML = innerHTML;

    if(!parentElement.nextElementSibling) {
        parentElement.parentElement.appendChild(element);
    }
    else {
        parentElement.parentElement.insertBefore(element, parentElement.nextElementSibling);
    }
    if(elementClass === "textEditor") {
        setTimeout(function () {
            formatiFrames();
        }, 100);
        renderNode(element, "textEditorAddCell");
    }

    if(elementClass === "drawEditor") {
        formatDrawElements();
        renderNode(element, "textEditorAddCell");
    }
}

function maskElementPassing(requestResponse, parentElement, elementClass) {
    if(requestResponse.readyState == 4 && requestResponse.status == 200) {

        var response = JSON.parse(requestResponse.responseText);
        if(response.statusCode == 200) {

            renderElementAddToDom(parentElement, elementClass, response.elementInner);
        }
    }
}

function renderNode(parentElement, nodeClass) {

    makeHttpRequest( function() {
        maskElementPassing(this, parentElement, nodeClass); },
    {
        "renderElement" : true,
        "element" : nodeClass
    }
    );
}

