
function showFriendRequests(button) {
    deactivateActivateButton(button)
    return false;
}

function deactivateActivateButton(button) {
    let elements = button.parentElement.parentElement.parentElement.getElementsByClassName("profileMenuActive");
    if(elements.length > 0) {
        if(elements[0] == button) {
            return;
        }
        elements[0].classList.remove("profileMenuActive");
    }
    button.classList.add("profileMenuActive");
}

function editProfile(button) {

    //Unset another already active button
    deactivateActivateButton(button);
    var inputForms = document.getElementById("userDetails").getElementsByTagName("input");
    for(let inputForm of inputForms) {
        inputForm.removeAttribute("disabled");
    }

    let saveButton = document.getElementById("saveChangesButton");
    saveButton.classList.remove("hidden");
    saveButton.innerHTML = "Save";
    saveButton.classList.remove("errorButton");
    saveButton.classList.remove("successButton");
}

function profileShow(button) {
    deactivateActivateButton(button);

    var inputForms = document.getElementById("userDetails").getElementsByTagName("input");
    for(let inputForm of inputForms) {
        inputForm.setAttribute("disabled", "");
    }

    document.getElementById("saveChangesButton").classList.add("hidden");
}

function saveProfileInformation(informationContainer) {

    let inputs = informationContainer.parentElement.getElementsByTagName("input");
    let myNewData = {};
    for(let input of inputs) {
        if(input.value.length > 2) 
            myNewData[input.name] = input.value;
        else {
            console.log(input.value);
            informationContainer.classList.add("errorButton");
            informationContainer.innerHTML = "Error";
        }
    }

    myNewData["user"] = true;
    myNewData["changeUserDetails"] = true;

	makeHttpRequest(function() {
		if(this.readyState == 4 && this.status == 200) {

            let button = document.getElementById("saveChangesButton");
            console.log(this.responseText);
			var response = JSON.parse(this.responseText);
	
			if(response.statusCode == 200) {
                button.innerHTML = "Success";
                button.classList.remove("errorButton");
                button.classList.add("successButton");
                return;
            }
            button.innerHTML = "Error";
            button.classList.add("errorButton");
            button.classList.remove("successButton");
		}
	}, 	myNewData
    );

    return false;

}


function changeProfilePicture(input) {
    fileUpload(input);
    return false;
}


function refreshImage(node) {
    var address;
    if(node.src.indexOf('?')>-1)
     address = node.src.split('?')[0];
    else 
     address = node.src;
    node.src = address+"?time="+new Date().getTime();
}

function fileUpload(publisher) {

    let files = publisher.files;
    let formData = new FormData();

    for (let file of files) {
        formData.append('files[]', file);
    }

    formData.append('profileImage', 'true');

    var request = new XMLHttpRequest();

	request.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
               refreshImage(document.getElementById("profileImageRefresh"));
            }
        }
    };
    
	request.open('POST', '/PHP/demo.php');
	request.send(formData);

	return false;

}






