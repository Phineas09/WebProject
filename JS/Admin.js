function drawLineLoggedUsersChart() {

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {

            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                document.getElementById("usersChartData").innerHTML = response.elementInner;
            }
        }
    },
        {
            "stats" : true,
            "loggedUsersChars" : true
        }
    );
};

function getOnlineUsers() {

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                
                document.getElementById("onlineUsersDisplay").innerHTML = response.online;
            }
        }
    },
        {
            "stats" : true,
            "onlineUsers" : true
        }
    );

};

function getUsersTableData(pattern = null) {

    data = {};
    data["stats"] = true;
    data["usersTable"] = true;

    if(pattern) {
        data["pattern"] = pattern;
    }

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                document.getElementById("usersTableData").innerHTML = response.usersTable;
            }
        }
    },
        data
    );
}

function adminAddUser(publisher) {

    let row = publisher.parentElement.parentElement;

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {
            
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                document.getElementById("mobileAddUserButton").firstChild.nextSibling.classList.add("hidden");
                document.getElementById("usersTable").classList.add("hidden");
                let userDetails = document.getElementById("userDetails");
                userDetails.classList.remove("hidden");
                userDetails.innerHTML = response.userDetails;
            }
        }
    },
        {
            "admin" : true,
            "userDetails" : true
        }
    );
}

function adminEditUser(publisher) {

    let row = publisher.parentElement.parentElement;

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                document.getElementById("mobileAddUserButton").firstChild.nextSibling.classList.add("hidden");
                document.getElementById("usersTable").classList.add("hidden");
                let userDetails = document.getElementById("userDetails");
                userDetails.classList.remove("hidden");
                userDetails.innerHTML = response.userDetails;
            }
        }
    },
        {
            "admin" : true,
            "userDetails" : true,
            "userId" : row.firstChild.nextSibling.innerHTML
        }
    );
}

function returnToUserTable(publisher) {

    document.getElementById("mobileAddUserButton").firstChild.nextSibling.classList.remove("hidden");
    document.getElementById("usersTable").classList.remove("hidden");
    document.getElementById("userDetails").classList.add("hidden");
}

function adminSaveUser(publisher) {

    let userDetailsDiv = publisher.parentElement.parentElement;

    let inputs = userDetailsDiv.getElementsByTagName("input");

    let postData = {};

    for(let input of inputs) {
        postData[input.name] = input.value;
    }

    postData["admin"] = true;

    (postData["userId"] == -1) ?  (postData["addUser"] = true) : (postData["changeDetails"] = true);

    console.log(postData);

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let button = document.getElementsByName("showStatus")[0];
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                button.classList.add("successButton");
                button.innerHTML = "Success";
                getUsersTableData();
            }
            else {
                button.classList.add("errorButton");
                button.innerHTML = "Error";
            }
        }
    },
        postData
    );
    

}

function adminDeleteUser(publisher) {

    let row = publisher.parentElement.parentElement;

    if(confirm("Do you really want to delete user " + row.childNodes[3].innerHTML + "?")){
        makeHttpRequest( function() {
            if(this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                var response = JSON.parse(this.responseText);
                if(response.statusCode == 200) {
                    getUsersTableData();
                }
            }
        },
            {
                "admin" : true,
                "deleteUser" : true,
                "userId" : row.firstChild.nextSibling.innerHTML
            }
        );
    }
    return;
    
} 

function adminSearchUser(searchWord) {
    (searchWord == "") ? getUsersTableData() : getUsersTableData(searchWord);
}
