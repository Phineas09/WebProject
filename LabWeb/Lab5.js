var elements = document.getElementsByClassName("element");

for(let elem of elements) {
    if(elem.hasAttribute("tool")) {
        elem.onclick = isBomb;
    }   
    else{
        elem.onclick = notBomb;
    }

}

function isBomb(event) {

    alert("Game Over!");
    location.reload();
}

function notBomb(event) {

    let element = event.target.parentElement;
    element.innerHTML = String(countRight(element) + countLeft(element)  + countBellowBomb(element) + countAboveBomb(element));
    
    element.setAttribute("found", "true");
    hasWon();
}

function hasWon() {
    var elements = document.getElementsByClassName("element");
    for(let elem of elements) {
        if(elem.hasAttribute("tool") || elem.hasAttribute("found") )
        {
            continue;

        }
        else{
            return;
        }
    }
    alert("Game Won!");


}

function countAboveBomb(element) {
    if(element.previousSibling.previousSibling) {
        if(element.previousSibling.previousSibling.hasAttribute("tool")) {
            return 1;
        }
        else{
            return 0;
        }
    }
    return 0;
}

function countBellowBomb(element) {
    if(element.nextSibling.nextSibling) {
        if(element.nextSibling.nextSibling.hasAttribute("tool")) {
            return 1;
        }
        else{
            return 0;
        }
    }
    return 0;
}

function countLeft(element) {
    if((element.parentElement.id != "1")) {
        let above = document.getElementById(parseInt(element.parentElement.id)-1);
            if(above) {
            above = above.childNodes[parseInt(element.getAttribute("poz"))];
            if(above) {
                if(above.hasAttribute("tool")) {
                    return 1 + countAboveBomb(above) + countBellowBomb(above);
                }
                else{
                    return countAboveBomb(above) + countBellowBomb(above);
                }
            }
            return 0;
        }
    }
    return 0;

}

function countRight(element) {
    if((element.parentElement.id != "4")) {
        let above = document.getElementById(parseInt(element.parentElement.id)+1);
        if(above) {
            above = above.childNodes[parseInt(element.getAttribute("poz"))];
            if(above) {
                if(above.hasAttribute("tool")) {
                    return 1 + countAboveBomb(above) + countBellowBomb(above);
                }
                else{
                    return countAboveBomb(above) + countBellowBomb(above);
                }
            }
            return 0;
        }
    }
    return 0;

}


