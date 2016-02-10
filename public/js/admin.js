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

    currentFrame++;

    if (currentFrame>frames.length) {
        currentFrame=0;
    }

    $(video).attr("src", frames[currentFrame]);
    $(video).attr("data-current-frame", currentFrame);

    timer = setTimeout(function(){ changeThumb(video); }, 1000);
}

function clearAjaxCSS() {
    $(".successAjax").removeClass("successAjax", "slow");
    $(".errorAjax").removeClass("errorAjax", "slow");
}

$( document ).ready(function() {
    alert("entro");
    $( ".ajax-form" ).submit(function( event ) {
        var action = $(this).attr("action");
        var form = $(this);
        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            var data = $.parseJSON(data);
            if (data['status'] == 1) {
                form.closest('.coloreable').addClass('successAjax');
                setTimeout("clearAjaxCSS()", 1000);
            } else {
                form.closest('.coloreable').addClass('errorAjax');
                setTimeout("clearAjaxCSS()", 1000);
            }
        });
        event.preventDefault();
    });
});