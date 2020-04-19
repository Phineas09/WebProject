function enableDraw(publisher) {

    console.log("Enbled Drawing");
    if(activeDraw) {
        activeDraw.paint.unBind();
    }

    if(activeiFrame) {
        closeiFrame();
    }

    activeDraw = publisher;
    window.ondblclick = closeActiveElement;
    activeDraw.paint.init();
}

function drawExecCmd(command, publisher, toolChange = null) {
    var target = publisher.parentElement.parentElement.nextElementSibling;

    if(target.paint == null) {
        target.paint = new Paint(target);
        target.paint.activeTool = "line";
        target.paint.lineWidth = 1;
        target.paint.selectedColor = "#000000";
        target.paint.init();
    }

    if(command == "foreColor") {
        target.paint.selectedColor = toolChange;
        return;
    }

    if(toolChange) {
        if(toolChange == "linewidth") {
            target.paint.lineWidth = command; 
        }
        else {
            target.paint.activeTool  = toolChange;
        }
    }

    if(command == "undo") {
        target.paint.undoPaint();
    }

    if(command == "downlaod") {

        //! Work needs to be done here
        
        var image = target.toDataURL("image/png", 1.0).replace("image/png", "image/octet-stream");
        var link = document.createElement("a");
        link.download = "my-image.png";
        link.href = image;
        link.onclick = (e) => {return false;}; 
        link.click();
    }
    drawEditorToolBoxActivate(publisher);
}

function toggleActiveElementFromDiv(targetDiv) {
    var alreadyActive = targetDiv.querySelector(".activeToolBoxElement");
    //If it exists disable it
    if(alreadyActive) {
        alreadyActive.classList.toggle("activeToolBoxElement");
    }
}

function drawEditorToolBoxActivate(button) {
    //Find previously active element
    toggleActiveElementFromDiv(button.parentElement);

    //If we picked an optional tool, disable evereything else
    if(button.getAttribute("data-tool") == "optional") {
        toggleActiveElementFromDiv(button.parentElement.previousElementSibling);
        button.parentElement.nextElementSibling.classList.add("restrict");
    } 
    if(button.getAttribute("data-tool") == "command") 
        return;

    if(button.getAttribute("data-tool") == "regular") {
        toggleActiveElementFromDiv(button.parentElement.nextElementSibling);
        button.parentElement.nextElementSibling.nextElementSibling.classList.remove("restrict");
    }    

    button.classList.toggle("activeToolBoxElement");
}

function formatDrawElements() {
    var canvases = document.getElementsByName('drawCanvas');
    for(let canvas of canvases) {
        if(canvas.paint == null) {
            canvas.paint = new Paint(canvas);
            canvas.paint.activeTool = "line";
            canvas.paint.lineWidth = 1;
            canvas.paint.selectedColor = "#000000";
        }
    }
}

function increaseDrawHeight(publisher, percent) {
    publisher.parentElement.parentElement.nextElementSibling.paint.increaseHeight(percent);
}
