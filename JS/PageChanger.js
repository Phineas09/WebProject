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

	changePage() {

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
			{
				"pageChange" : true,
				"page" : this._page
			},
			this._link
		);
	}
	
}

// ! Page history thingy

/*
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
*/
// Page management part !@!


var pageChanger = new PageChanger("home");
var currentPage =  "Home";
var historyStack = [];

var pageDictionary = {

	"Home" : pageChangeHome,
	"Problems" : pageChangeProjects

};

function changePage(page) {
	pageDictionary[page]();
	historyStack.push(currentPage);
	currentPage = page;
}

function pageChangeProjects() {

	pageChanger.page = "Problems";

	pageChanger.changePage();
	document.getElementById("mainHtml").setAttribute("theme", "blue")

	loadProblemList();
	executeAnimationsLoading();

	return false;
}

function pageChangeHome() {

	pageChanger.page = "Home";
	pageChanger.changePage();
	document.getElementById("mainHtml").removeAttribute("theme", "blue");
	executeAnimationsLoading();
	
}

function searchProblemsSort(pattern, sortBy="author", order="asc") {

	console.log(pattern);
	var options = document.getElementById("sortBySelector");
	sortBy = options.options[options.selectedIndex].text;
	console.log(sortBy);

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






