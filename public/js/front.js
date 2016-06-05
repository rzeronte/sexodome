var timer;

function outThumb(video){
    var status = $(video).attr("data-status");
    var thumbs = $(video).attr("data-thumbs");
    var currentFrame = $(video).attr("data-current-frame");
    var frames = jQuery.parseJSON( thumbs );

    clearTimeout(timer);
}

function changeThumb(video) {
    var status = $(video).attr("data-status");
    var thumbs = $(video).attr("data-thumbs");
    var currentFrame = parseInt($(video).attr("data-current-frame"));
    var frames = jQuery.parseJSON( thumbs );

    if (frames.length <= 1) {
        return false;
    }
    currentFrame++;

    if (currentFrame>frames.length) {
        currentFrame=0;
    }

    if (frames[currentFrame]) {
        preloadImage(frames[currentFrame], video);
    }
    $(video).attr("data-current-frame", currentFrame);

    timer = setTimeout(function(){ changeThumb(video); }, 1000);
}

function preloadImage(source, destElem) {
    var image = new Image();

    image.src = source;

    image.onload = function () {
        var cssBackground = 'url(' + image.src + ')';

        $(destElem).css('background-image', cssBackground);
    };
}

function OpenInNewTab(url) {
    var win = window.open(url, '_blank');
}

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

//        if (readCookie('nudeangelscams_pop') == null) {
//            createCookie('nudeangelscams_pop', 1, 1);
//            OpenInNewTab('http://www.nudeangelscams.com');
//        }

