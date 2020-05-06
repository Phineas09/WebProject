// JavaScript Document


class PageChanger {
	
	constructor(page, link="/PHP/demo.php") {
		this._page = page;
		this._link = link;
	}
	
	get page() {
		return this._page;
	}

	get link() {
		return this._link;
	}

	set page(newPage) {
		this._page = newPage;
	}

	set link(newLink) {
		this._link = newLink;
	}

	changePage(userFormatted = false) {

		let data = {"pageChange" : true,
					"page" : this._page};

		if(userFormatted == true) {
			data["userFormatted"] = true;
		}			

		//force the page change request to be syncronus @!s
		makeHttpRequest( function() {
			if(this.readyState == 4 && this.status == 200) {
				//console.log(this.responseText);
				var response = JSON.parse(this.responseText);
				if(response.statusCode == 200) {

					document.getElementById("content").innerHTML = response.pageContent;

					executeAnimationsLoading();

				}
				else {
					//! define 404
					console.log("error 404 define it!@!");
				}
			}
		},
			data,
			this._link,
			false
		);
	}

	changePageFormatted(data) {

		data["pageChange"] = true;
		data["page"] = this._page;
		data["userFormatted"] = true;

		//force the page change request to be syncronus @!s
		makeHttpRequest( function() {
			if(this.readyState == 4 && this.status == 200) {
				//console.log(this.responseText);
				var response = JSON.parse(this.responseText);
				if(response.statusCode == 200) {

					document.getElementById("content").innerHTML = response.pageContent;
					executeAnimationsLoading();
				}
				else {
					console.log("error 404 define it!@!");
				}
			}
		},
			data,
			this._link,
			false
		);
	}

}

// ! Page history thingy

jQuery(document).ready(function($) {

    if (window.history && window.history.pushState) {

        window.history.pushState('', 'dummy', './');

        $(window).on('popstate', function() {

			if(historyStack.length != 0) {
				window.history.pushState('', 'dummy', './'); 
				currentPage = historyStack.pop();
				pageDictionary[currentPage]();
			}
			else{
				window.history.back();
			}
        });
    }
});


function goBackOnePage() {
	currentPage = historyStack.pop();
	pageDictionary[currentPage]();
}

// Page management part !@!


var pageChanger = new PageChanger("home");
var currentPage =  "Home";
var historyStack = [];

var pageDictionary = {

	"Home" : pageChangeHome,
	"Problems" : pageChangeProjects,
	"NewProblem" : pageChangeNewProblem,
	"ProfilePage" : pageChangeProfilePage,
	"AdminUsers" : pageChangeAdminUsers,
	"AdminStatistics" : pageChangeAdminStatistics,
	"ContactPage" : pageChangeContactPage,
	"ViewProblem" : pageChangeViewProblem,
	"ProfilePageAnotherUser" : pageChangeProfilePage
};

function changePage(page) {
	pageDictionary[page]();
	historyStack.push(currentPage);
	currentPage = page;
}

function pageChangeProfilePageUser(userId) {

	historyStack.push(currentPage);
	// ?
	pageChanger.page = "ProfilePageAnotherUser";
	currentPage = "ProfilePageAnotherUser";

	data = {
		"userId" : userId,
		"ProfilePageAnotherUser" : true
	};
	pageChanger.changePageFormatted(data);
	document.getElementById("mainHtml").setAttribute("theme", "blue");

}

function pageChangeProfilePage() {
	pageChanger.page = "ProfilePage";
	//User formatted page
	pageChanger.changePage(true);
	document.getElementById("mainHtml").setAttribute("theme", "blue");
	
	return false;
}

function pageChangeViewProblem() {

	pageChanger.page = "ViewProblem";
	pageChanger.changePage();

	
	setTimeout(function () {
		formatEditorElements();
	}, 200);

	return false;
}

function pageChangeContactPage() {

	pageChanger.page = "ContactPage";
	pageChanger.changePage();
	document.getElementById("mainHtml").removeAttribute("theme", "blue");
	executeAnimationsLoading();
	return false;
}

function pageChangeAdminUsers() {

	pageChanger.page = "AdminUsers";
	pageChanger.changePage();

	document.getElementById("mainHtml").setAttribute("theme", "blue");
	getUsersTableData();

	return false;
}


function pageChangeAdminStatistics() {

	pageChanger.page = "AdminStatistics";
	pageChanger.changePage();

	document.getElementById("mainHtml").setAttribute("theme", "blue");
	
	getOnlineUsers();
	drawLineLoggedUsersChart();



	return false;
}

function pageChangeProjects() {

	pageChanger.page = "Problems";

	pageChanger.changePage();
	document.getElementById("mainHtml").setAttribute("theme", "blue");
	loadProblemsOptions();
	loadProblemList();
	executeAnimationsLoading();
	return false;
}

function pageChangeNewProblem() {

	pageChanger.page = "NewProblem";
	pageChanger.changePage();

	//? Nu am habar de ce e nevoie de asta 1??
	setTimeout(function () {
		formatEditorElements();
	}, 200);
	return false;
}

function loadProblemsOptions() {
	makeHttpRequest(function() {
		if(this.readyState == 4 && this.status == 200) {

			var response = JSON.parse(this.responseText);
	
			if(response.statusCode == 200) {
				document.getElementById("problemsOptions").innerHTML = response.options;
			}
		}
	}, 	
	{
		"user" : true,
		"problemsOptions" : true
	}
	);

	return false;
}

function pageChangeHome() {

	pageChanger.page = "Home";
	pageChanger.changePage();
	document.getElementById("mainHtml").removeAttribute("theme", "blue");
	executeAnimationsLoading();
}

function searchProblemsSort(pattern, sortBy="author", order="asc") {

	var options = document.getElementById("sortBySelector");
	sortBy = options.options[options.selectedIndex].text;

	makeHttpRequest(
		onSuccessLoadProblemList, 	
		{
			"contentLoader" : true,
			"loadProblems" : true,
			"patternSearch" : true,
			"pattern" : pattern,
			"sortBy" :	sortBy.toLowerCase(),
			"order" : order
 		}
	);

	return false;
}

function onSuccessLoadProblemList() {
	if(this.readyState == 4 && this.status == 200) {
		var response = JSON.parse(this.responseText);

		if(response.statusCode == 200) {
			document.getElementById("project-list").innerHTML = response.problemList;
			projectListFloatIn();
		}
		if(response.statusCode == 412) {
			alert("No pending problems avalable!");
		}
	}
}


function loadProblemList(sortBy = "author", order = "asc") {

	makeHttpRequest(onSuccessLoadProblemList, 	
		{
			"contentLoader" : true,
			"loadProblems" : true,
			"sortBy" :	sortBy.toLowerCase(),
			"order" : order
 		}
	);

	return false;
}

function pendingApproval() {

	makeHttpRequest(onSuccessLoadProblemList, 	
		{
			"contentLoader" : true,
			"loadProblems" : true,
			"loadPenginApproval" :	true
 		}
	);

	return false;
}






