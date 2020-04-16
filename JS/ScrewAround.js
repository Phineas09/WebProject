
//replace path ?
//window.history.replaceState("", '', 'bar2.html');
/*
jQuery(document).ready(function($) {

    if (window.history && window.history.pushState) {

        window.history.pushState('forward', null, './');

        $(window).on('popstate', function(event) {

            alert('Back button was pressed.');
            window.history.pushState('forward', null, './');
        });
    }
});
*/

function a() { console.log("a"); };
function b() { console.log("b"); };

var F = {"home":a,"problems":b};

F["home"](); // log "a"
F["problems"](); // log "b"









function screwAround() {  //Gets true if the window is closed, else gets false
    console.log("vava");
	makeHttpRequest(function () {
		
		if(this.readyState == 4 && this.status == 200) {

			var response = JSON.parse(this.responseText);

			if(response.statusCode == 200) {
                console.log("done?");
			}
		}
		},
		{
			"testing" : true
		}
	);

	return false;
}