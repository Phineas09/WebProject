window.onload = () => {formatEditorElements();}

window.onmessage = iFramePipeReceive;

var activeiFrame = null;
var activeDraw = null;

function iFramePipeReceive(event) {

    event.data.hasOwnProperty("frameHeight")&&
    (document.getElementById(event.data.frameOrigin).style.height=
    `${event.data.frameHeight+30}px`);


    //!!!! If has preview tag don't do nothign
    if(event.data.hasOwnProperty("frameElement")) {
        openiFrame(event.data.frameElement);
    }

    if(event.data.hasOwnProperty("canvasElement")) {
        enableDraw(document.getElementById(event.data.canvasElement));
    }
}

function openiFrame(frameElement) {
    var iFrame = document.getElementById(frameElement);

    if(iFrame.hasAttribute("previewOnly"))
        return;

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

function renderElementAddToDom(parentElement, elementClass, innerHTML, async) {

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
        renderNode(element, "textEditorAddCell", async);
    }

    if(elementClass === "drawEditor") {
        formatDrawElements();
        renderNode(element, "textEditorAddCell", async);
    }
}

function maskElementPassing(requestResponse, parentElement, elementClass, async) {
    if(requestResponse.readyState == 4 && requestResponse.status == 200) {

        var response = JSON.parse(requestResponse.responseText);
        if(response.statusCode == 200) {

            renderElementAddToDom(parentElement, elementClass, response.elementInner, async);
        }
    }
}

function renderNode(parentElement, nodeClass, async = true) {

    makeHttpRequest( function() {
        maskElementPassing(this, parentElement, nodeClass, async); },
    {
        "renderElement" : true,
        "element" : nodeClass
    },
        "/PHP/demo.php",
        async
    );
}


//* Place for problem save

function getEditorsData(contentContainer) {
    problemData = {};
    problemData["editors"] = [];

    let minCaracters = 0;

    for(let child of contentContainer.childNodes) {
        if(child.classList) {
            if(child.classList.contains("drawEditor")) {
                let canvasData = {};
                let canvas = child.getElementsByTagName("canvas")[0];
                console.log(canvas);
                canvasData["editor"] = "drawEditor";
                canvasData["height"] = canvas.paint.editorHeight;
                canvasData["content"] = canvas.paint.editorContent;
                problemData["editors"].push(canvasData);
            }
            if(child.classList.contains("textEditor")) {
                let frameData = {};
                let frame = child.getElementsByTagName("iframe")[0];
                console.log(frame);
                frameData["editor"] = "textEditor";
                frameData["content"] = frame.contentDocument.getElementsByTagName("body")[0].innerHTML;
                minCaracters = minCaracters + frameData["content"].length;
                problemData["editors"].push(frameData);
            }
        }
    }
    return problemData;
}

function submitNewProblem() {

    var contentContainer = document.getElementById("content");
    let problemName = document.getElementById("problem_Name").value;

    if(!problemName) {
        //!error
        return;
    }

    //Get the drawing and text sequentially

    if(minCaracters < 200) {
        //! Error not enough caracters
    }

    let formData = new FormData();

    let problemData = getEditorsData(contentContainer);

    formData.append('problemData', JSON.stringify(problemData));
    formData.append('title', problemName);

    //Append files 

    let sourceFile = document.getElementById("sourceFile").files;
    let testFiles = document.getElementById("testFiles").files;

    if(sourceFile.length == 0 || testFiles.length == 0) {
        //! Error 
        return;
    }

    formData.append('sourceFile', sourceFile[0]);
    
    for (let file of testFiles) {
        formData.append('testFiles[]', file);
    }

    formData.append('submitProblem', 'true');

    var request = new XMLHttpRequest();
	request.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                console.log("Done?");
            }
        }
    };
    
	request.open('POST', '/PHP/demo.php');
	request.send(formData);

    return false;
}

function _getLastDomElement(className) {

    let element = document.getElementsByClassName(className);
    element = element[element.length - 1];
    return element;
}

function loadElementsViewProblem(problemData, problemName) {

    problemData = problemData.editors;

    let addElement = _getLastDomElement("textEditorAddCell");

    let problemNameInput = document.getElementById("problem_Name");
    problemNameInput.value = problemName;

    for(let editor of problemData) {
        if(editor["editor"] == "drawEditor") {
            renderNode(addElement, "drawEditor", false);
            let canvas = _getLastDomElement("drawEditor");
            canvas = canvas.childNodes[2];
            canvas.paint.canvasHeight = editor["height"];
            canvas.paint.editorContent = editor["content"];
            canvas.setAttribute("previewOnly", "true");
        }
        if(editor["editor"] == "textEditor") {
            renderNode(addElement, "textEditor", false);
            let textEditor = _getLastDomElement("textEditor");
            textEditor = textEditor.childNodes[2];
            textEditor.contentDocument.getElementsByTagName("body")[0].innerHTML = editor["content"];
            textEditor.setAttribute("previewOnly", "true");
        }

        addElement = _getLastDomElement("textEditorAddCell");
        addElement.classList.add("hidden");
    }
}

function loadViewProblem() {
	if(this.readyState == 4 && this.status == 200) {
        var response = JSON.parse(this.responseText);
		if(response.statusCode == 200) {
            //Problem Data parse
            var problemData = JSON.parse(response.problemData);
            loadElementsViewProblem(problemData, response.problemName);
		}
	}
}

var problemEditId = null;

function viewProblem(viewProjectButton, projectGiven = -1) {

    let projectId = 0;
    if(projectGiven == -1) {
        projectId = viewProjectButton.parentElement.previousElementSibling.previousElementSibling.previousElementSibling.innerHTML;
    }
    else 
        projectId = projectGiven;

    problemEditId = projectId;

    changePage("ViewProblem");
    //Make request to get problems info

    makeHttpRequest( loadViewProblem,
    {
        "problemsManager" : true,
        "problemId" : projectId
    }
    );
}

function editProblem(publisher) {

    publisher.classList.add("hidden");
    publisher.nextElementSibling.classList.remove("hidden");

    let problemNameInput = document.getElementById("problem_Name");
    problemNameInput.removeAttribute("disabled");
    let addCells = document.getElementsByClassName("textEditorAddCell");

    for(let addCell of addCells) {
        addCell.classList.remove("hidden");
    }

    let textEditors = document.getElementsByClassName("textEditor");

    for(let editor of textEditors) {
        let textEditor = editor.childNodes[2];
        textEditor.removeAttribute("previewOnly");
    }


    let drawEditors = document.getElementsByClassName("drawEditor");

    for(let editor of drawEditors) {
        let drawEditor = editor.childNodes[2];
        drawEditor.removeAttribute("previewOnly");
    }

    //Do something about thing down  

    document.getElementById("projectViewEdit").classList.remove("hidden");
    document.getElementById("projectViewPrev").classList.add("hidden");
    document.getElementById("modifyProblem").parentElement.classList.remove("hidden");
    document.getElementById("submitSolution").parentElement.classList.add("hidden");

}

function cancelEdit(publisher) {
    //Cancel edit and revert changes 
    if(confirm("Canceling edit will revert any changes!")) {
        viewProblem(null, problemEditId);
        historyStack.pop();


    }
}

async function downloadInputFilesForProblem() {
    let problemId = problemEditId;
    makeHttpRequest( function () {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);   
            if(response.statusCode == 200) {
                let anchor = document.createElement('a');
                anchor.href =response.arhivePath;
                anchor.setAttribute("download", "");
                anchor.click();
            }
        }
    },
    {
        "problemsManager" : true,
        "downloadInputArhive" : true,
        "problemId" : problemId
    },
        "/PHP/demo.php",
        false
    );
}

function downloadProblemFiles(event) {
    event.preventDefault();
    downloadInputFilesForProblem();
}

function submitModifyProblem() {

    let overwrite = document.getElementById("appendToExisting").checked;

    var contentContainer = document.getElementById("content");
    let problemName = document.getElementById("problem_Name").value;

    if(!problemName) {
        //!error
        return;
    }

    let formData = new FormData();

    let problemData = getEditorsData(contentContainer);
    
    formData.append('problemData', JSON.stringify(problemData));
    formData.append('title', problemName);
    //Problem Id
    formData.append('problemId', problemEditId);

    if(overwrite)
        formData.append("overwrite", overwrite);

    let sourceFile = document.getElementById("editSourceFile").files;
    let testFiles = document.getElementById("testFiles").files;

    if(sourceFile.length != 0)
        formData.append('sourceFile', sourceFile[0]);
    
    if(testFiles.length != 0) {
        for (let file of testFiles) {
            formData.append('testFiles[]', file);
        }
    }

    formData.append('modifyProblem', 'true');
    formData.append('problemsManager', 'true');

    var request = new XMLHttpRequest();
	request.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                document.getElementById("modifyProblem").classList.add("successButton");
            }
        }
    };
    
	request.open('POST', '/PHP/demo.php');
	request.send(formData);

    return false;
}


function submitSolution() {

    let sourceFile = document.getElementById("sourceFile").files;

    let formData = new FormData();

    if(sourceFile.length != 0)
        formData.append('sourceFile', sourceFile[0]);

    formData.append("problemsManager", true);
    formData.append("submitSolution", true);
    formData.append("problemId", problemEditId);
    
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                alert(response.problemResults);
            }
            else alert(response.message);
        }
    };
    
    request.open('POST', '/PHP/demo.php');
    request.send(formData);

}
