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

function getUsersTableData() {

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                document.getElementById("usersTableData").innerHTML = response.usersTable;
            }
        }
    },
        {
            "stats" : true,
            "usersTable" : true
        }
    );


}

function adminAddUser(publisher) {

    document.getElementById("mobileAddUserButton").firstChild.nextSibling.classList.add("hidden");
    document.getElementById("usersTable").classList.add("hidden");
    document.getElementById("userDetails").classList.remove("hidden");
}

function adminEditUser(publisher) {
    document.getElementById("mobileAddUserButton").firstChild.nextSibling.classList.add("hidden");
    document.getElementById("usersTable").classList.add("hidden");
    document.getElementById("userDetails").classList.remove("hidden");
}

function returnToUserTable(publisher) {

    document.getElementById("mobileAddUserButton").firstChild.nextSibling.classList.remove("hidden");
    document.getElementById("usersTable").classList.remove("hidden");
    document.getElementById("userDetails").classList.add("hidden");
}






