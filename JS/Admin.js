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

getOnlineUsers();
drawLineLoggedUsersChart();