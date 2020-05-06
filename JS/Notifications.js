//Scris in jquery pentru comfort

var initial_data = [
    {
        "text" : "No notifications yet",
        "name" : "",
        "date" : "",
        "read" : false
    }
]

$(document).ready(function() {

    fetchAllNotifications();
    window.setInterval(fetchNotifications , 60000);

    $('.notification').click(function(){

        //Mark notifications as read in db

        makeHttpRequest(function () {},
        {
            "activeCheck" : true,
            "markNotificationsRead" : true
        });

        if(!$(document).find('.notification-dropdown').hasClass('dd')){
            hide_dropdown()
        }else{
            $('.notification-dropdown').removeClass('dd').addClass('dropdown-transition');
            setNofitication(0);
            saveSessionStorage();
        }
    })
    
    $(document).on('click',function(e){
        var target = $(e.target);
        if(!target.closest('.notification').length && !target.closest('.dropdown-transition').length){
            if(!$(document).find('.notification-dropdown').hasClass('dd')){
                hideNotificationDropdown();
            }
        }
    })

});

function fetchAllNotifications() {

    makeHttpRequest(function() {
        if(this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                if(response.notifications != "") {
                    sessionStorage.removeItem('notification');
                    sessionStorage.setItem("notification", response.notifications);
                }
                loadNotifications();
            }
        }
    }
        ,
        {
            "activeCheck" : true,
            "fetchNotificationsAll" : true
        });
}

function fetchNotifications(format = null) {

    if(!format) {
        fetchFunction = function () {
            if(this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                var response = JSON.parse(this.responseText);
                if(response.statusCode == 200) {
                    
                    if(parseInt(response.newNotifications) == 1) {
                        sessionStorage.removeItem('notification');
                        sessionStorage.setItem("notification", response.notifications);
                        loadNotifications();
                    }
                }
            }
        };
    }
    else {
        fetchFunction = format
    }

	makeHttpRequest(fetchFunction
	,
	{
        "activeCheck" : true,
        "fetchNotifications" : true
	});
}

function loadNotifications() {
    var data = ((sessionStorage.getItem('notification') != undefined) ? JSON.parse(sessionStorage.getItem('notification')) : initial_data);
    var count = 0;
    $(document).find('.notification-dropdown .items').html("");

    if(data) {
        for(var i =0; i < data.length; i++) {
            let item = data[i];
            //More format to be done here 
            let html = $(
                '<div class="list-item noti">' + 
                    ((item['image']) ? ( String('<div class="image fl"><img src="' + item['image'] + '"></div>')) : ('')) +
                    '<div class="text fl"><b class="name fl">' + item['name'] + '</b>' + item['text'] + 
                    '<sub class="date">' + item['date'] +'</sub>' +
                '</div></div>');
            $(html).data('notification', item);
            if(!item['read']){
                count = count + 1;
            }else{
                $(html).addClass('background-white');
            }
            $(document).find('.notification-dropdown .items').append(html);
        }
        saveSessionStorage();                                           
    }
    setNofitication(count);

};

function hideNotificationDropdown(){
    $(document).find('.notification-dropdown').removeClass('dropdown-transition').addClass('dd');
    $(document).find('.notification-dropdown').find('.list-item').addClass('background-white');
    setNofitication(0);
    saveSessionStorage();
}

function saveSessionStorage() {
    var notificationList = [];
    $(document).find('.notification-dropdown .items .list-item').each(function(){
        var elementData = $(this).data('notification')
        var newElement = {
            text : elementData['text'],
            name : elementData['name'],
            image : elementData['image'],
            date : elementData['date']
        }
        if($(this).hasClass('background-white') || !$(document).find('.notification-dropdown').hasClass('dd')){
            newElement['read'] = true;
        }else{
            newElement['read'] = false;
        }
        notificationList.push(newElement);
    })
    sessionStorage.removeItem('notification');
    sessionStorage.setItem("notification", JSON.stringify(notificationList));
}

function setNofitication(count){
    let notifCount = $(document).find('.notify-count').attr('count',count);
    $(document).find('.notify-count .value').text(count);
    let notifAbove = $(document).find('.count1');
    document.title = (count > 0) ?  ('('+count+') - MTArena Coding Challanges') : ('MTArena Coding Challanges');
    (count == 0) ? notifAbove.addClass("hidden") : notifAbove.removeClass("hidden");
}

function setNotification(count) {
    let notifCount = $(document).find('.notify-count').attr('count',count);
    $(document).find('.notify-count .value').text(count);

    document.title = (count > 0) ?  ('('+count+') - MTArena Coding Challanges') : ('MTArena Coding Challanges');
    (count == 0) ? notifCount.addClass("hidden") : notifCount.removeClass("hidden");
}


//!

function closeChatOutside(event) {
    var chat = document.getElementById("chatContainer");
    let targetElement = event.target; // clicked element
    let openChat = document.getElementById("chatFloatOpen");
    //console.log(targetElement);
    do {
        if (targetElement == chat) {
            return;
        }
        // Go up the DOM
        targetElement = targetElement.parentNode;
        if(targetElement != null) {
            if(targetElement == openChat)
                return;
        }
    } while (targetElement);
    closeChat();
}

function openChat() {
    document.getElementById("chatFloatOpen").classList.add("hidden");
    document.getElementById("chatContactList").classList.remove("hidden");
    document.getElementById("chatContainer").classList.add("chatContainerActive");

    document.addEventListener("click", closeChatOutside);
}

function closeChat() {
    document.getElementById("chatContainer").classList.remove("chatContainerActive");
    document.getElementById("chatFloatOpen").classList.remove("hidden");
    document.getElementById("chatContactList").classList.add("hidden");

    document.removeEventListener("click", closeChatOutside);
}



