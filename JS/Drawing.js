
var paint = new Paint(document.getElementById("drawCanvas"));
paint.activeTool = "line";
paint.lineWidth = 1;
paint.selectedColor = "#000000";
paint.init();

function drawExecCmd(command, publisher, toolChange = null) {

    var target = publisher.parentElement.parentElement.nextElementSibling;

    if(command == "foreColor") {
        paint.selectedColor = toolChange;
        console.log(toolChange);
        return;
    }

    if(toolChange) {
        if(toolChange == "linewidth") {
            paint.lineWidth = command; 
        }
        else {
            paint.activeTool  = toolChange;
        }
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








