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

    $( ".google-show-info" ).click(function() {
        $(this).parent().parent().parent().find('.detail-analytics').toggle('fast');
    });

    $( ".iframe-show-info" ).click(function() {
        $(this).parent().parent().parent().find('.detail-iframe').toggle('fast');
    });

    $( ".logo-show-info" ).click(function() {
        $(this).parent().parent().parent().find('.detail-logo').toggle('fast');
    });

    $( ".colors-show-info" ).click(function() {
        $(this).parent().parent().parent().find('.detail-colors').toggle('fast');
    });

    $( ".form-update-color-data" ).submit(function( event ) {
        var action = $(this).attr("action");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                $('.modal .modal-body').html("<div class='alert alert-success' role='alert'>Color saved successful</div>");
            } else {
                $('.modal .modal-body').html("<div class='alert alert-danger' role='alert'>Have an error! Try in a few minutes...</div>");
            }
            $('.modal').modal()

        });
        event.preventDefault();
    });

    $( ".form-update-google-data" ).submit(function( event ) {
        var action = $(this).attr("action");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                $('.modal .modal-body').html("<div class='alert alert-success' role='alert'>Google info saved successful</div>");
            } else {
                $('.modal .modal-body').html("<div class='alert alert-danger' role='alert'>Have an error! Try in a few minutes...</div>");
            }
            $('.modal').modal()

        });
        event.preventDefault();
    });

    $( ".form-update-iframe-data" ).submit(function( event ) {
        var action = $(this).attr("action");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                $('.modal .modal-body').html("<div class='alert alert-success' role='alert'>IFrame saved successful</div>");
            } else {
                $('.modal .modal-body').html("<div class='alert alert-danger' role='alert'>Have an error! Try in a few minutes...</div>");
            }
            $('.modal').modal()

        });
        event.preventDefault();
    });

    $( ".submit-feed-site-form" ).submit(function( event ) {
        var action = $(this).attr("action");
        var action_ajax = $(this).attr("action-ajax");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                $('.modal .modal-body').html("<div class='alert alert-success' role='alert'>Your jobs is queued successful</div>");
            } else {
                $('.modal .modal-body').html("<div class='alert alert-danger' role='alert'>Your job cant be queued, try in a few seconds...</div>");
            }
            $('.modal').modal()

        });
        event.preventDefault();
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

    $( ".seo-info-keywords" ).click(function() {
        var action = $(this).attr("data-url");

        $("#SEOInfoModal .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#SEOInfoModal  .modal-body").html(data);
        });
    });

    $( ".btn-select-thumb" ).click(function() {
        var action = $(this).attr("data-url");

        $("#previewModal .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#previewModal .modal-body").html(data);

            $( ".scene-thumb-image-selector" ).click(function() {
                var img = $(this).attr("src");
                var scene_id = $(this).attr("data-scene-id");
                var thumb_number = $(this).attr("data-thumb-number");
                $('.selectedThumb'+scene_id).val(thumb_number);

                $('.scene-thumb-image-selector').css("border", "none");
                $(this).css("border", "solid 4px green");
                $(".selected-thumb-for-"+scene_id).attr("src", img);
            });

        });
    });

    $( ".btn-spin-text" ).click(function() {
        var action = $(this).attr("data-url");

        $("#previewModal .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#previewModal .modal-body").html(data);

            $( ".scene-thumb-image-selector" ).click(function() {
                var img = $(this).attr("src");
                var scene_id = $(this).attr("data-scene-id");
                var thumb_number = $(this).attr("data-thumb-number");
                $('.selectedThumb'+scene_id).val(thumb_number);

                $('.scene-thumb-image-selector').css("border", "none");
                $(this).css("border", "solid 4px green");
                $(".selected-thumb-for-"+scene_id).attr("src", img);
            });

        });
    });

    $( "#add_site_type" ).change(function() {
        if ($(this).val() == 0) {
            $('.div_input_domain').hide();
            $(".div_input_domain :input").prop('required', null);
            $(".div_input_name :input").prop('required', true);
            $('.div_input_name').show();
        } else {
            $('.div_input_domain').show();
            $('.div_input_name').hide();
            $(".div_input_domain :input").prop('required', true);
            $(".div_input_name :input").prop('required', null);
        }
    });

    $( ".selector_feeds_site" ).change(function() {
        var site_id = $(this).val();
        var url = $(this).attr('data-ajax');
        var selectorCategories = $(this).parent().parent().find('.selector_feed_categories');

        $.ajax({
            url: url,
            data: {site_id: site_id},
            method: 'get'
        }).done(function( data ) {
            if (data.length > 0) {
                selectorCategories.html(data);
            } else {
                selectorCategories.html("<option value=''>--No categories--</option>");
            }
        });
    });

    console.log("[DEBUG] All load");
});

function checkSubdomain(me) {
    var subdomain = $(me).val();

    if (subdomain.length <=3) {
        $(".result_subdomain").html('min. 3 characters');
        return false;
    }

    var action = $("#form_check_subdomain").attr("action");
    $("#form_check_subdomain .subdomain").val(subdomain);

    $.ajax({
        url: action,
        data: $("#form_check_subdomain").serialize(),
        method: 'post'
    }).done(function( data ) {
        jsonData = $.parseJSON(data);
        if (jsonData["status"] == true) {
            $(".result_subdomain").html('<p>Subdomain is <b>available</b></p>');
            $(".result_subdomain").removeClass('check_domain_ok');
            $(".result_subdomain").removeClass('check_domain_ko');

            $(".result_subdomain").addClass('check_domain_ok');
        } else {
            $(".result_subdomain").html('<p>Subdomain is <b>unavailable</b></p>');
            $(".result_subdomain").removeClass('check_domain_ko');
            $(".result_subdomain").removeClass('check_domain_ko');

            $(".result_subdomain").addClass('check_domain_ko');
        }
    });
}

function checkDomain(me) {
    var domain = $(me).val();

    if (domain.length <=3) {
        $(".result_domain").html('min. 3 characters');
        return false;
    }

    var action = $("#form_check_domain").attr("action");
    $("#form_check_domain .domain").val(domain);

    $.ajax({
        url: action,
        data: $("#form_check_domain").serialize(),
        method: 'post'
    }).done(function( data ) {
        jsonData = $.parseJSON(data);
        if (jsonData["status"] == true) {
            $(".result_domain").html('<p>Domain is <b>available</b></p>');
            $(".result_domain").removeClass('check_domain_ok');
            $(".result_domain").removeClass('check_domain_ko');

            $(".result_domain").addClass('check_domain_ok');
        } else {
            $(".result_domain").html('<p>Domain is <b>unavailable</b></p>');
            $(".result_domain").removeClass('check_domain_ko');
            $(".result_domain").removeClass('check_domain_ko');

            $(".result_domain").addClass('check_domain_ko');
        }
    });
}