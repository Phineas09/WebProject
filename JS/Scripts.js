
window.addEventListener('resize', function () {
	document.getElementById("nav-toggle").checked = false;	
});

window.onscroll = navScrollFunction;



var navbar = document.getElementById("navbarHeader");
var sticky = navbar.offsetTop;

function navScrollFunction() {

	var homePageContainer = document.getElementById("home-content-container-section");

	if(window.pageYOffset > sticky) {
		navbar.style.position = "fixed";
		navbar.style["transition"] = "0.1s ease-in-out";		
		//navbar.style["background-color"] = "rgba(164, 167, 171,1)";
		navbar.style["box-shadow"] = "0 0px 12px 1px #333";

		if(homePageContainer)	
			homePageContainer.style.height = "90vh";
	}
	else {
		
		navbar.style.position = "relative";
		//navbar.style["background-color"] = "white";
		navbar.style["box-shadow"] = "none";
	
		if(homePageContainer)	
			homePageContainer.style.height = "100vh";

	}
}

function makeBlurry() {
	
	if(document.getElementById("nav-toggle").checked == true) {
		document.addEventListener('click', closeNavOutside, true);
		document.documentElement.style.setProperty('--theme-darkness', 0.7);
	}
	else {	
		document.removeEventListener('click', closeNavOutside, true);
		document.documentElement.style.setProperty('--theme-darkness', 1);
	}
}

function closeNavOutside(event) {
	var loginForm = document.getElementById("navbarHeader");
	let targetElement = event.target; // clicked element
	//console.log(targetElement);
    do {
		if (targetElement == loginForm) {
			return;
		}
        // Go up the DOM
		targetElement = targetElement.parentNode;
		if(targetElement != null)
			if(targetElement.tagName == "LI") //If the elements is a li then a click to change the page was made so change the page
				break;
    } while (targetElement);
	document.getElementById("nav-toggle").checked = false;
	makeBlurry();
}

	
// * Jquery for floating In

(function($) {

	$.fn.visible = function(partial) {
		var $t            = $(this),
			$w            = $(window),
			viewTop       = $w.scrollTop(),
			viewBottom    = viewTop + $w.height(),
			_top          = $t.offset().top,
			_bottom       = _top + $t.height(),
			compareTop    = partial === true ? _bottom : _top,
			compareBottom = partial === true ? _top : _bottom;

		return ((compareBottom <= viewBottom) && (compareTop >= viewTop));
	};
	
	$.fn.exists = function () {
		return this.length !== 0;
	};

})(jQuery);

jQuery.expr[':'].regex = function(elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ? 
                        matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels,'')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
}

var assignModules = (name) => { var temp = $(name); return (temp.exists() == true) ? temp : null;}

function homeAnimation() {

	var	allModules = assignModules(":regex(class, module-[mlr].*)");

	if(allModules != null) {
		allModules.each(function(i, element) {
			var element = $(element);
			if (element.visible(true)) {
				var classToAdd = "come-in" + "-" + element.attr('class').split("-")[1];
				element.removeClass(element.attr('class').split(" ")[0]);
				element.addClass(classToAdd);
			} 
	  });
	}
}

// ! Add here more functions for another animations

var projectListFloatIn = function() {

    var projectElements = $(".project-element-hidden");

    var timeout = 10;

    for (var element of projectElements) {
        if(($(element)).visible(true)) {
            setTimeout(
                (element) => 
                {
                    element.classList.remove("project-element-hidden");
                }, timeout, element);

            timeout = timeout + 300;
        }
    }
};

function executeAnimationsLoading() {
	projectListFloatIn();
	homeAnimation(); 
}

executeAnimationsLoading();

$(window).scroll(function(event) {
	executeAnimationsLoading(); 
});


function makeHttpRequest(onReadyFunction, stringData, link="/PHP/demo.php", async = true) {
	
	var request = new XMLHttpRequest();

	request.onreadystatechange = onReadyFunction;
	
	var data = JSON.stringify(stringData);
	request.open('POST', link, async);
	request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	request.send(data);

	return false;
}


//! LightBox

function openModal() {
	document.getElementById("myModal").style.display = "block";
	window.scrollTo(0, 0);
	document.getElementById("body").style.overflow = "hidden";
  }
  
  function closeModal() {
	document.getElementById("myModal").style.display = "none";
	document.getElementById("body").style.overflow = "scroll";

  }
  
  var slideIndex = 1;
  showSlides(slideIndex);
  
  function plusSlides(n) {
	showSlides(slideIndex += n);
  }
  
  function currentSlide(n) {
	showSlides(slideIndex = n);
  }
  
  function showSlides(n) {
	var i;
	var slides = document.getElementsByClassName("mySlides");
	var dots = document.getElementsByClassName("demo");
	var captionText = document.getElementById("caption");
	if (n > slides.length) {slideIndex = 1}
	if (n < 1) {slideIndex = slides.length}
	for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none";
	}
	for (i = 0; i < dots.length; i++) {
		dots[i].className = dots[i].className.replace(" active", "");
	}
	slides[slideIndex-1].style.display = "block";
	dots[slideIndex-1].className += " active";
	captionText.innerHTML = dots[slideIndex-1].alt;
  }

