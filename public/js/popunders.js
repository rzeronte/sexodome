//createPopUnder("http://yahoo.com", "width=800,height=510,scrollbars=1,resizable=1,toolbar=1,location=1,menubar=1,status=1,directories=0", once_per_session = 0);

function createPopUnder(url, winfeatures, once_per_session)
{
    if (once_per_session==0) {
        loadpopunder(url, winfeatures)
    } else {
        loadornot(url, winfeatures)
    }
}

function get_cookie(Name) {
    var search = Name + "="
    var returnvalue = "";
    if (document.cookie.length > 0) {
        offset = document.cookie.indexOf(search)
        if (offset != -1) { // if cookie exists
            offset += search.length
            // set index of beginning of value
            end = document.cookie.indexOf(";", offset);
            // set index of end of cookie value
            if (end == -1)
                end = document.cookie.length;
            returnvalue=unescape(document.cookie.substring(offset, end))
        }
    }
    return returnvalue;
}

function loadornot(url, winfeatures){
    if (get_cookie('popunder')==''){
        loadpopunder(url, winfeatures)
        document.cookie="popunder=yes"
    }
}

function loadpopunder(url, winfeatures){
    win2=window.open(url,"",winfeatures)
    win2.blur()
    window.focus()
}