google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawLineLoggedUsersChart);

function drawLineLoggedUsersChart() {

    makeHttpRequest( function() {
        if(this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {

                var lineChart = new google.visualization.LineChart(document.getElementById('line-chart'));

                var data = new google.visualization.DataTable();
                data.addColumn('date', 'Date');
                data.addColumn('number', 'Logged Users');

                for(key in response.elementInner) 
                    data.addRow([new Date(key), parseInt(response.elementInner[key])]);
               
                    var options = {
                        title: 'Users Per Day',
                        curveType: 'function',
                       
                        legend: { position: 'top' }
                };
            
                lineChart.draw(data, options);
            }
            else {
                //! define 404
                console.log(this.responseText);
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