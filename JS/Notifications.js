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

    $("#chatInputMessage").on('keyup', function (e) {
        if (e.keyCode === 13) {
            $('.chatInputSendButton').click();
        }
    });

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

function fetchFriendsInfo() {
    makeHttpRequest(function() {
        if(this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                document.getElementById("chatContactItems").innerHTML = response.friendList;
            }
        }
    },
    {
        "chat" : true,
        "getFriendsInfoFormatted" : true
    });
}


function fetchNotifications(format = null) {

    if(!format) {
        fetchFunction = function () {
            if(this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
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
    do {
        if (targetElement == chat) {
            return;
        }
        targetElement = targetElement.parentNode;
        if(targetElement != null) {
            if(targetElement == openChat)
                return;
        }
    } while (targetElement);
    closeChat();
}

function closeChatMessageBox(event) {
    var chat = document.getElementById("chatBox");
    let targetElement = event.target; // clicked element
    let openChat = document.getElementById("chatContainer");
    do {
        if (targetElement == chat) {
            return;
        }
        targetElement = targetElement.parentNode;
        if(targetElement != null) {
            if(targetElement == openChat)
                return;
        }
    } while (targetElement);
    closeChatMessage();
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


var currentChatWindow = null;
var currentUserId = null;


function openChatMessage(publisher) {

    formatChatMessage(publisher);

    document.getElementById("chatContainer").classList.remove("chatContainerActive");
    document.getElementById("chatContactList").classList.add("hidden");
    document.getElementById("chatBox").classList.add("chatBoxActive");

    document.removeEventListener("click", closeChatOutside);
    document.addEventListener("click", closeChatMessageBox);


    currentUserId = document.getElementById("hiddenChatBoxId").innerHTML;

    if(!currentChatWindow && currentUserId) {
        currentChatWindow = setInterval(pullMessages, 1000);
    }

}

function closeChatMessage() {

    document.getElementById("chatBox").classList.remove("chatBoxActive");

    document.getElementById("chatContactList").classList.remove("hidden");
    document.getElementById("chatContainer").classList.add("chatContainerActive");

    document.removeEventListener("click", closeChatMessageBox);
    document.addEventListener("click", closeChatOutside);

    if(currentChatWindow && currentUserId) {
        clearInterval(currentChatWindow);
        currentChatWindow = null;
        currentUserId = null;
    }

}

function sendChatMessage(publisher) {
    inputValue = document.getElementById("chatInputMessage").value;
    document.getElementById("chatInputMessage").value = "";

    let chatBox = $('#chatBoxMessageArea');
    let lastBoxChild = chatBox.children().last();
    if(lastBoxChild.hasClass('message-right')) {
        let html = $('<div class="bubble bubble-dark">' + inputValue + '</div>');
        lastBoxChild.append(html);
    } 
    else {
        let html = $('<div class="message message-right"><div class="bubble bubble-dark">' + inputValue + '</div></div>');
        chatBox.append(html);
    }

    chatBox.scrollTop(chatBox.prop("scrollHeight"));

    let userId = document.getElementById("hiddenChatBoxId").innerHTML;

    pushMessage(inputValue, userId);

}

function formatChatMessage(publisher) {

    //Format header, make pull request for messages
    
    let userId = publisher.getElementsByClassName("hiddenChatId")[0].innerHTML;
    let userName = publisher.getElementsByClassName("name")[0].innerHTML;
    let profileImage = publisher.childNodes[1].src;

    document.getElementById("chatHeaderImage").src = profileImage;
    document.getElementById("chatHeaderName").innerHTML = userName;
    document.getElementById("hiddenChatBoxId").innerHTML = userId;

    //Clear all messages
    //document.getElementById("chatBoxMessageArea").innerHTML = "";

    fillMessages(userId);
}


function fillMessages(userId) {
    makeHttpRequest(function() {
        if(this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                let chatBox = $('#chatBoxMessageArea');
                chatBox.html(response.messages);
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            }
        }
    }
        ,
        {
            "chat" : true,
            "getMessagesUser" : true,
            "userId" : userId
        });
}

function pushMessage(message, userId) {

    makeHttpRequest(function() {
        if(this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
            }
        }
    }
        ,
        {
            "chat" : true,
            "pushMessage" : true,
            "userId" : userId,
            "message" : message
        });
}

function pullMessages() {

    console.log("Requesting massages with -> ", currentUserId);

    makeHttpRequest(function() {
        if(this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                let chatBox = $('#chatBoxMessageArea');
                let html = $(response.messages);
                chatBox.append(html);
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            }
        }
    }
        ,
        {
            "chat" : true,
            "pullMessages" : true,
            "userId" : currentUserId
        });
}

function ArrayDiff(A, B) {
    return A.filter(function (a) {
        return B.indexOf(a) == -1;
    });
}

var onlineUsers = null;

function messagesOnline() {

    makeHttpRequest(function() {
        if(this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText)
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {

                if(onlineUsers) {

                    let newOnlineUsersResp = (response.onlineUsers.split(',').filter(function(i){return i !== " " }));

                    let offlineNumber = (ArrayDiff(onlineUsers, newOnlineUsersResp)).length;
                    let newOnlineUsers = (ArrayDiff(newOnlineUsersResp, onlineUsers));

                    let reloaded = 0;
                    if(newOnlineUsers.length) {
                        console.log(newOnlineUsers);
                        reloaded = 1;

                        fetchFriendsInfo();
                        setInterval(makeFriendLoggedNotification(newOnlineUsers), 200);
                    }

                    if(offlineNumber) {
                        if(!reloaded)
                            fetchFriendsInfo();
                    }

                    onlineUsers = newOnlineUsersResp;

                }
                else {
                    onlineUsers = (response.onlineUsers.split(',').filter(function(i){return i !== " " }));
                }

                if(response.newMessages) {
                    let messagesFrom = response.newMessages.split(',').filter(function(i){return i !== " " });
                }

            }
        }
    }
        ,
        {
            "chat" : true,
            "messagesOnline" : true
        });
}


function makeFriendLoggedNotification(newOnlineUsers) {

    let containers = document.getElementsByClassName("hiddenChatId");

    let newNofitications = [];

    for(let container of containers) {
        let userId = container.innerHTML;
        if(newOnlineUsers.includes(userId)) {

            let parent = container.parentElement;

            let userName = parent.getElementsByClassName("name")[0].innerHTML;
            let imgSrc = parent.childNodes[1].src;
            let data = {};

            data["text"] = "Is now Online!"; 
            data["date"] = ""; 
            data["read"] = false; 
            data["name"] = userName; 
            data["image"] = imgSrc; 
            newNofitications.push(data);

        }
    }
    if(newNofitications.length) {
        let oldNotifications = JSON.parse(sessionStorage.getItem('notification'));

        for(let notif of newNofitications)
            oldNotifications.unshift(notif);
        sessionStorage.removeItem('notification');
        sessionStorage.setItem("notification", JSON.stringify(oldNotifications));
        loadNotifications();
    }
}

function chatBoxOpenProfile(publisher) {
    pageChangeProfilePageUser(currentUserId);
}
