// JavaScript Document
var signUpButton = document.getElementById("signUp");
var signInButton = document.getElementById("signIn");
var container = document.getElementById("container");

var signUpButtonLittle = document.getElementById("sign-Up");
var signInButtonLittle = document.getElementById("sign-In");

var openedLogin = false;

checkIsLogged();



//var url = window.location.toString();
//window.location = url.replace('/function=search/', 'function=loginsearch&user=admin&password=admin');
//window.history.pushState("object or string", "Title", "/newPath");

//Mark user as offline when closing the website
window.addEventListener('beforeunload', function () {
	//console.log("here");
	//logOut(true);
});

signUpButton.addEventListener('click', function() {
	container.classList.add('right-panel-active');
});

signUpButtonLittle.addEventListener('click', function() {
	container.classList.add('right-panel-active');
});

signInButtonLittle.addEventListener('click', function() {
	container.classList.remove('right-panel-active');
});

signInButton.addEventListener('click', function() {
	container.classList.remove('right-panel-active');
});

//Put all cookies in a global structure

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}


function recuringLoggedInRequest() {
	makeHttpRequest(function () {
		if(this.readyState == 4 && this.status == 200) {
			console.log(this.response);
		}
	},
	{
		"activeCheck" : true
	});
}

function setLoggedInNavbar() {
	makeHttpRequest(function () {
		if(this.readyState == 4 && this.status == 200) {
			//console.log(this.response);

			var response = JSON.parse(this.responseText);

			if(response.statusCode == 200) {
				var navbarLogin = document.getElementById("hoverMenu");		
				navbarLogin.innerHTML = response.navbar;
			}

			window.setInterval(recuringLoggedInRequest , 30000);


		}
	},
	{
		"loginBar" : true,
		"user" : true
	});
}

function setLoggedOutNavbar() {
	
	window.clearInterval();
	var navbarLi = document.getElementById("hoverMenu");
	navbarLi.innerHTML = '<a id="navLogin" href="" onclick="popUpLogin(); return false">Login</a>';

}


function checkIsLogged() {
	
	var request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		
		if(this.readyState == 4 && this.status == 200) {
			//console.log(this.response);
			var response = JSON.parse(this.responseText);

			if(response.statusCode == 200) {
				setLoggedInNavbar();
			}
		}
	}
	
	var stringData = {
		"loginVerification" : true,
		"logger" : true
	};

	var data = JSON.stringify(stringData);

	request.open('POST', '/PHP/demo.php');
	request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	request.send(data);

	return false;
}



function escPressed(event) {
    if(event.key === "Escape") {
        popUpLogin();
    }
}

function popUpLogin() {
	if(openedLogin == false) {
		document.getElementById("container").style.display = "block";
		setTimeout(function () {
			
			//document.getElementById("content").style.backgroundColor = "rgba(0,0,0,0.3)";
			document.getElementById("container").style.opacity = "1";
		}, 1);
		
		//Add nice function for close

		document.documentElement.style.setProperty('--theme-darkness', 0.7);
		
		window.addEventListener("keydown", escPressed);
		document.addEventListener('click', closeLoginOutside, true);
		
		//Disable scroll event
		
		document.getElementById("body").style.overflow = "hidden";
		
		openedLogin = true;
	}
	else {
		//document.getElementById("content").style.backgroundColor = "white";	
		document.getElementById("container").style.opacity = "0";

		setTimeout(function () {
			document.getElementById("container").style.display = "none";	
		}, 400);
		
		//Remove those functions

		document.documentElement.style.setProperty('--theme-darkness', 1);
		
		window.removeEventListener("keydown", escPressed);
		document.removeEventListener('click', closeLoginOutside, true);
		
		//Reactivate Scroll
		
		document.getElementById("body").style.overflow = "";

		openedLogin = false;
	}
}

function closeLoginOutside(event) {
	
	var loginForm = document.getElementById("container");
	let targetElement = event.target; // clicked element
	var navbarLogin = document.getElementById("navLogin");
	var getStarted = document.getElementById("getStartedButton");
	
	
    do {
		if (targetElement == loginForm || targetElement == navbarLogin || targetElement == getStarted) {
			return;
		}
        // Go up the DOM
		targetElement = targetElement.parentNode;
    } while (targetElement);
	popUpLogin();
}

//Functions for login / Sign UP

 //action="./PHP/demo.php" method="post"
//onload="document.SignInForm.reset(); document.SignUpForm.reset(); "

//Reset after submission


//signIn function signInMessageBox

document.getElementById("forgotPassword").addEventListener("click", function(event) {
	event.preventDefault();
	forgotPassword();
	return false;
});


function forgotPassword() {
	
	var signInForm = document.getElementById("SignUpForm");
	
	var messageBox = document.getElementById("forgotPasswordMessageBox");
	
	var successMessageBox = document.getElementById("signInMessageBox");
	successMessageBox.className = "";
	
	var email = signInForm.elements["email"].value;
		
	messageBox.className = "";
	
	if(email === "") {

		messageBox.classList.add("error-message");
		messageBox.innerHTML = "Please enter the email address.";
		messageBox.style.display = "block";
		return;
	}
	
	var request = new XMLHttpRequest();
	
	request.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {		
			successMessageBox.classList.add("info-message");
			successMessageBox.innerHTML = "An email with your new password has been sent!";
			successMessageBox.style.display = "block";
		}
	}

	var stringData = {
		"forgotPassword" : true,
		"email" : email,
		"logger" : true
	};
	
	var data = JSON.stringify(stringData);
	
	request.open('POST', '/PHP/demo.php');
	request.setRequestHeader("Content-Type", "application/json;charset=UTF-8")
    request.send(data);

	return;
}



function openAccount() {
	
	console.log("To work");
	return false;
}


function sendSignIn(form) {
	
	var elements = form.elements;
	
	var password = elements["password"].value;
	var email = elements["email"].value;
	
	var messageBox = document.getElementById("signInMessageBox");
	messageBox.className = "";
	
	
	var request = new XMLHttpRequest();
	
	request.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			
			var response = JSON.parse(this.responseText);
			
			if(response.statusCode == 200) {
				messageBox.classList.add("info-message");
				messageBox.innerHTML = "Logged in successfully";
				messageBox.style.display = "block";

				setLoggedInNavbar();
			}
						
			if(response.statusCode == 415) {
				messageBox.classList.add("error-message");
				messageBox.innerHTML = "Given email or password do not match.";
				messageBox.style.display = "block";
				

			}
		}
	}

	var stringData = {
		"login" : true,
		"password" : password,
		"email" : email,
		"logger" : true
	};
	
	var data = JSON.stringify(stringData);
	
	request.open('POST', '/PHP/demo.php');
	request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    request.send(data);

	return false;
}

function sendSignUp(form) {
	
	var elements = form.elements;
	
	var userName = elements["name"].value;
	var password = elements["password"].value;
	var email = elements["email"].value;
	
	var mailErrorMessage = document.getElementById("emailSignUpError");
	
	var successSignUp = document.getElementById("signUpSuccess");
	
	mailErrorMessage.style.display = "none";
	successSignUp.style.display = "none";
	
	//form.reset();
		
	var request = new XMLHttpRequest();
	
	request.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			
			var response = JSON.parse(this.responseText);
			
			if(response.statusCode == 200) {
				successSignUp.style.display = "block";			
				//maybe make the login 			
			}
						
			if(response.statusCode == 409) {
				
				mailErrorMessage.innerHTML = "Given email address is already used.";
				mailErrorMessage.style.display = "block";
			}
		}
	}
	
	
	var stringData = {
		"register" : true,
		"name" : userName,
		"password" : password,
		"email" : email,
		"logger" : true
	};
	
	var data = JSON.stringify(stringData);
	
	request.open('POST', '/PHP/demo.php');
	request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    request.send(data);

	
	return false;
}

//Facebook Login

//Load the sdk

(function(d, s, id) {                      // Load the SDK asynchronously
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


window.fbAsyncInit = function() {
	FB.init({
			appId      : '1109332766085834',
			xfbml      : true,
			cookie     : true,                     // Enable cookies to allow the server to access the session.		
			version    : 'v2.8'
	});


	FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
		statusChangeCallback(response);      // Returns the login status.
	});
	
};

function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus or after loginButton finish
	if (response.status === 'connected') {   // Logged into your webpage and Facebook.
		facebookLoginRequest();
		//make request to PHP
	}
}

function facebookLogin() {	
	FB.login(function(response) {
		if (response.status === 'connected') {
			facebookLoginRequest();
		}
	}, {scope: 'public_profile,email' });
}

function facebookLogout() {
    FB.getLoginStatus(function(response) {
        if (response && response.status === 'connected') {
            FB.logout();
        }
    });
}

function _oauthLoginFunction() {
	
	if(this.readyState == 4 && this.status == 200) {

		var response = JSON.parse(this.responseText);

		var messageBox = document.getElementById("signInMessageBox");
		if(response.statusCode == 200) {
			messageBox.classList.add("info-message");
			messageBox.innerHTML = "Logged in successfully";
			messageBox.style.display = "block";

			setLoggedInNavbar();
		}
	}
}

function facebookLoginRequest() {                      // Test
	FB.api('/me', { locale: 'en_US', fields: 'name, email' }, function(response) {

		//Send to the server informations about the user if exists just set Cookie and change navbar else register user and same thing
		var messageBox = document.getElementById("signInMessageBox");
		messageBox.className = "";
		
		makeHttpRequest(
			_oauthLoginFunction,
			{
				"logger" : true,
				"oauth" : true,
				"method" : "facebook",
				"name" : response.name,
				"oauthId" : response.id,
				"email" : response.email
			}
		);	
		return false;
	});
}

//Login with google+ ?

var GoogleAuth;


function onLoadGoogleCallback() {
// Load the API's client and auth2 modules.
// Call the initClient function after the modules load.
	gapi.load('auth2', initClient);	
}

function initClient() {
// Retrieve the discovery document for version 3 of Google Drive API.
// In practice, your app can retrieve one or more discovery documents.
// Initialize the gapi.client object, which app uses to make API requests.
// Get API key and client ID from API Console.
// 'scope' field specifies space-delimited list of access scopes.
	gapi.client.init({
	  	client_id: '254971779215-3aiovaankogntvcg2nmrt7j9clc12luj.apps.googleusercontent.com',
		scope: 'profile email openid',
		cookie_policy : "none",
		
	}).then(function (objectAuth) {
		GoogleAuth = gapi.auth2.getAuthInstance();
		// Listen for sign-in state changes.
		
		GoogleAuth.isSignedIn.listen(updateStatusChange); 	// 	A function that takes a boolean value. listen() passes true to this function when the user signs in, and false when the user signs out. 
		//If a user is logged in then call setSignInStatus
		if(GoogleAuth.isSignedIn.get()) {
			setSigninStatus();
		}
		
	}, function() {});
	
	
}

function googlePlusLogin() {
		if (GoogleAuth.isSignedIn.get()) {
			GoogleAuth.signOut();
		} else {
			(async () => {
				try{		
			  		await GoogleAuth.signIn();
				}
				catch(e) {
					console.log(e);
				}
			})();		
		}
	return false;
}

function googleLogout() {
	if(GoogleAuth.isSignedIn.get()) {
		GoogleAuth.disconnect();	
	}	
	return false;
}

function setSigninStatus() {

	var userDetails = GoogleAuth.currentUser.get().getBasicProfile();
	var messageBox = document.getElementById("signInMessageBox");
	messageBox.className = "";

	makeHttpRequest(
		_oauthLoginFunction,
		{
			"logger" : true,
			"oauth" : true,
			"method" : "google",
			"name" : userDetails.getName(),
			"oauthId" : userDetails.getId(),
			"email" : userDetails.getEmail()
		} 
	);

	return false;
}

function updateStatusChange(isSignedIn) {
	if(isSignedIn) 
		setSigninStatus();
	else {
		GoogleAuth.disconnect();
	}
}

function logOut(logOutOnClose) {  //Gets true if the window is closed, else gets false

	makeHttpRequest(function () {
		
		if(this.readyState == 4 && this.status == 200) {

			var response = JSON.parse(this.responseText);
			console.log(this.responseText);
			if(response.statusCode == 200) {
				//Facebook Login or google Login

				if(logOutOnClose === "false") {
					if(response.oauth == "facebook")
						facebookLogout();
					if(response.oauth == "google")
						googleLogout();
				}
			}
			setLoggedOutNavbar();
			// ! Reload page after logout!
			location.reload();
		}
		},
		{
			"logger" : true,
			"logOut" : true,
			"logOutOnClose" : logOutOnClose
		}
	);

	return false;
}






