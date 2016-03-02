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

    $( ".btn-show" ).click(function() {

        $( "#book" ).toggle( "slow", function() {
            // Animation complete.
        });
    });

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

    $( ".btn-show" ).click(function() {
        console.log("entro");
    });

    $( ".btn-tag-tiers" ).click(function() {
        var action = $(this).attr("data-url");
        var scene = $(this).attr("data-scene-id");
        var site = $("#site_select_"+scene).val();

        $("#TagTiersModal .modal-body").html("Loading...");

        $.ajax({
            url: action+"?site="+site,
            method: 'get'
        }).done(function( data ) {
            $("#TagTiersModal .modal-body").html(data);
        });
    });

    $( ".btn-publication-info" ).click(function() {
        var action = $(this).attr("data-url");
        $("#TagTiersModal .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#TagTiersModal .modal-body").html(data);
        });
    });

    $( ".btn-preview-scene" ).click(function() {
        var action = $(this).attr("data-url");
        $("#previewModal .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#previewModal  .modal-body").html(data);
        });
    });

});