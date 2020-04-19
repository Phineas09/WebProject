
const TOOL_LINE = "line";
const TOOL_RECTANGLE = "rectangle";
const TOOL_CIRCLE = "circle";
const TOOL_TRIANGLE = "triangle";
const TOOL_PENCIL = "pencil";
const TOOL_BRUSH = "brush";
const TOOL_PAINT = "paint-bucket";
const TOOL_ERASER = "eraser";

class Paint {

    constructor(canvasElement) {
        this.canvas = canvasElement;
        this.context = this.canvas.getContext("2d");
        this.setCanvasScale();
        this.undoStack = [];
        
        this.undoLimit = 3;
        $('#' + this.canvas.id).dblclick(this.dbClickEvent.bind(this));
    }

    set activeTool(tool) {
        this.tool = tool;
    }

    set lineWidth(lineWidth) {
        this._lineWidth = lineWidth;
        this.context.lineWidth = lineWidth;
    }

    set selectedColor(color) {
        this.color = color;
        this.context.strokeStyle = this.color;
    }
 
    setCanvasScale() {

        var savedData = this.context.getImageData(0, 0, this.canvas.width, this.canvas.height);
        /*
            this.canvas.width = this.canvas.height *(this.canvas.clientWidth / this.canvas.clientHeight);
            this.canvas.height = this.canvas.width *(this.canvas.clientHeight / this.canvas.clientWidth);
        */
        var rect = this.canvas.getBoundingClientRect()
        let scaleX = rect.width / this.canvas.width;
        let scaleY = rect.height / this.canvas.height; 
        this.canvas.width = this.canvas.width * scaleX;
        this.canvas.height = this.canvas.height * scaleY;

        this.context.putImageData(savedData, 0, 0);
    };

    _getMouseCoordsCanvas(event) {
        var rect = this.canvas.getBoundingClientRect(),
        scaleX = this.canvas.width / rect.width,    // relationship bitmap vs. element for X
        scaleY = this.canvas.height / rect.height;  // relationship bitmap vs. element for Y
    
        return new Point (
            (event.clientX - rect.left) * scaleX,
            (event.clientY - rect.top) * scaleY    
        );
    }

    init() {
        console.log("Initi");
        window.onresize = () => this.setCanvasScale();
        $('#' + this.canvas.id).off();
        this.canvas.onmousedown = (event) => this.onMouseDown(event);
        this.setCanvasScale();
        this.canvas.classList.add("selectediFrame");
        this.canvas.previousElementSibling.classList.remove("hiddenEditorOptions");
        this.canvas.nextElementSibling.classList.add("hiddenEditorOptions");
    }

    dbClickEvent() {
        console.log("Called DB Click");
        var canvas = this.canvas;
            window.postMessage({
                canvasElement: canvas.id
            }, '*');
    }    
    
    increaseHeight(percent) {
        let element = $('#' + this.canvas.id);
        element.height(parseInt(element.height()) + percent);
        this.setCanvasScale();

    }

    unBind() {
        console.log("Unbinding");
        this.canvas.onmousedown = null;
        this.canvas.previousElementSibling.classList.add("hiddenEditorOptions");
        activeDraw = null;

        $('#' + this.canvas.id).dblclick(this.dbClickEvent.bind(this));
        
        window.removeEventListener("ondblclick", closeActiveElement);

        this.canvas.classList.remove("selectediFrame");
        this.canvas.previousElementSibling.classList.add("hiddenEditorOptions");
        this.canvas.nextElementSibling.classList.remove("hiddenEditorOptions");
    }

    onMouseDown(event) {
        this.savedData = this.context.getImageData(0, 0, this.canvas.width, this.canvas.height);
        if(this.undoStack.length >= this.undoLimit) {
            this.undoStack.shift();
        }
        this.undoStack.push(this.savedData);

        this.canvas.onmousemove = (event) => this.onMouseMove(event);

        document.onmouseup = (event) => this.onMouseUp(event);

        this.startPos = this._getMouseCoordsCanvas(event);

        if(this.tool == TOOL_PENCIL || this.tool == TOOL_BRUSH) {
            this.context.beginPath();
            this.context.moveTo(this.startPos.x, this.startPos.y);
        }
        else if (this.tool == TOOL_PAINT) {
            new Fill(this.canvas, this._round(this.startPos), this.color);
        }
        else if (this.tool == TOOL_ERASER) {
            this.context.clearRect(this.startPos.x, this.startPos.y,
                this._lineWidth +5, this._lineWidth +5 );
        }
    }

    _round(point) {
        return new Point(Math.round(point.x), Math.round(point.y));
    }

    onMouseMove(event) {
        this.currentPos = this._getMouseCoordsCanvas(event);

        switch(this.tool) {
            case TOOL_LINE:
            case TOOL_RECTANGLE: 
            case TOOL_CIRCLE:   
            case TOOL_TRIANGLE:
                this.drawShape();
                break;
            case TOOL_PENCIL:
                this.drawFreeLine(this._lineWidth); 
                break;
            case TOOL_BRUSH:
                this.drawFreeLine(parseInt(this._lineWidth) + 5);
                break;
            case TOOL_ERASER:
                this.context.clearRect(this.currentPos.x, this.currentPos.y,
                this._lineWidth + 5, this._lineWidth + 5); 
                break;
            default:
                break;    
        }
    }

    onMouseUp(event) {
        this.canvas.onmousemove = null;
        document.onmouseup = null;
    }

    drawShape() {

        this.context.putImageData(this.savedData, 0, 0);

        this.context.beginPath();
        if(this.tool == TOOL_LINE) {
            this.context.moveTo(this.startPos.x, this.startPos.y);
            this.context.lineTo(this.currentPos.x, this.currentPos.y);
        }
        else if (this.tool == TOOL_RECTANGLE) {
            this.context.rect(this.startPos.x, this.startPos.y, this.currentPos.x - this.startPos.x, this.currentPos.y - this.startPos.y);

        }
        else if (this.tool == TOOL_CIRCLE) {
            this.context.arc(this.startPos.x, this.startPos.y, this._calculateEuclidianDistance(), 0, 2 * Math.PI, false);
        }
        else if (this.tool == TOOL_TRIANGLE) {
            let triangleTop = new Point(this.startPos.x + (this.currentPos.x - this.startPos.x) / 2, this.startPos.y);
            this.context.moveTo(triangleTop.x, triangleTop.y);
            this.context.lineTo(this.startPos.x, this.currentPos.y);
            this.context.lineTo(this.currentPos.x, this.currentPos.y);
            this.context.lineTo(triangleTop.x, triangleTop.y);
        }
        this.context.stroke();
            
    }

    _calculateEuclidianDistance() {
        return (Math.sqrt(Math.pow(this.currentPos.x - this.startPos.x, 2) + Math.pow(this.currentPos.y - this.startPos.y, 2)));
    }

    drawFreeLine(lineWidth) {
        this.context.lineWidth = lineWidth;
        this.context.lineTo(this.currentPos.x, this.currentPos.y);
        this.context.stroke();
    }

    undoPaint() {
        if(this.undoStack.length > 0) {
            this.context.putImageData(this.undoStack.pop(), 0, 0);
        }
    }

};

class Point {
    constructor(x, y) {
        this.x = x;
        this.y = y;
    }
}

class Fill {
    //Flor Fill Algorithm

    constructor(canvas, point, color) {
        this.context = canvas.getContext("2d");
        this.imageData = this.context.getImageData(0, 0, canvas.width, canvas.height);
        const targetColor = this.getPixel(point);
        const fillColor = this.hexToRgba(color);
        this.fillStack = [];
        this.floorFill(point, targetColor, fillColor);
        this.execfillColor();
    }

    floorFill(point, targetColor, fillColor) {
        
        if(this.colorsMatch(targetColor, fillColor))
            return;

        const currentColor = this.getPixel(point);
        if(this.colorsMatch(currentColor, targetColor)) {

            this.setPixel(point, fillColor);
            //Smart bypass stack overflow

            this.fillStack.push([new Point(point.x + 1, point.y), targetColor, fillColor]);
            this.fillStack.push([new Point(point.x - 1, point.y), targetColor, fillColor]);
            this.fillStack.push([new Point(point.x, point.y + 1), targetColor, fillColor]);
            this.fillStack.push([new Point(point.x, point.y - 1), targetColor, fillColor]);
        }
    }

    execfillColor() {
        if(this.fillStack.length) {

            let range = this.fillStack.length;

            for(let i=0; i < range; i++) {
                this.floorFill(this.fillStack[i][0], this.fillStack[i][1], this.fillStack[i][2]);
            }

            this.fillStack.splice(0, range);
            this.execfillColor();
        }
        else {
            this.context.putImageData(this.imageData, 0, 0);
            this.fillStack = [];
        }
    }

    colorsMatch(colorA, colorB) {
        return colorA[0] === colorB[0] && colorA[1] === colorB[1] &&
        colorA[2] === colorB[2] && colorA[3] === colorB[3];
    }

    getPixel(point) {
        if(point.x < 0 || point.y < 0 || point.x >= this.imageData.width || point.y >= this.imageData.height) {
            return [-1, -1, -1, -1]; //Impossible color
        }
        else {
            const offset = (point.y * this.imageData.width + point.x) *4;
            return [this.imageData.data[offset +0],
                    this.imageData.data[offset +1],
                    this.imageData.data[offset +2],
                    this.imageData.data[offset +3]
            ];
        }
    }

    hexToRgba(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return [
            parseInt(result[1], 16),
            parseInt(result[2], 16),
            parseInt(result[3], 16),
            255
        ];
    }

    setPixel(point, fillColor) {
        const offset = (point.y * this.imageData.width + point.x) *4;
        this.imageData.data[offset + 0] = fillColor[0];    
        this.imageData.data[offset + 1] = fillColor[1];    
        this.imageData.data[offset + 2] = fillColor[2];    
        this.imageData.data[offset + 3] = fillColor[3];    
    }
}

