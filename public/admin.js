var timer;

$( document ).ready(function() {

    function eventFileUpload() {
        $('.fileupload').fileupload({
            dataType: 'json',
            done: function (e, data) {
                if (data.error) {
                  showGenericalSuccessMessage();
                } else {
                  showGenericalErrorMessage();
                  return;
                }

                $.each(data.result.files, function (index, file) {
                    var category_id = file.category_id;
                    var url = file.url;
                    var md5_url = file.md5_url;
                    $('.category-form-'+category_id).find("input[name='thumbnail']").val(url);
                    $('.category-form-'+category_id).find(".category-preview").attr('src', md5_url);
                });

            }
        });
    }
    eventFileUpload();

    $('#selector_site').on('loaded.bs.select', function (e) {
       $('.loading-panel-img').hide();
    });

    $( ".btn_site_menu_option" ).click(function() {
        $(this).toggleClass('btn-success');
        var divShow = $(this).attr('data-div-show');
        $(this).parent().parent().parent().parent().find('.' + divShow).toggle();
    });

    $( "#formAddPopunder" ).submit(function( event ) {
        var action = $(this).attr("action");
        var form = $(this);
        var actionAjaxPopunders = $(this).attr('data-ajax-popunders');

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'get'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                $.ajax({
                    url: actionAjaxPopunders,
                    data: form.serialize(),
                    method: 'get'
                }).done(function( data ) {
                    $('.container-ajax-popunders').html(data);
                    showGenericalSuccessMessage();
                });
            } else {
                showGenericalErrorMessage();
            }
        });
        event.preventDefault();
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
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }

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
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }

        });
        event.preventDefault();
    });

    $( ".form-create-cronjob" ).submit(function( event ) {
        var action = $(this).attr("action");
        var actionUpdateCronJobs = $(this).attr("data-update-cronjobs-url");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                $.ajax({
                    url: actionUpdateCronJobs,
                    data: $(this).serialize(),
                    method: 'get'
                }).done(function( data ) {
                    $('.cronjobs_ajax_container').html(data);
                    showGenericalSuccessMessage();
                });
            } else {
                showGenericalErrorMessage();
            }

        });
        event.preventDefault();
    });

    $( ".form-update-seo-data" ).submit(function( event ) {
        var action = $(this).attr("action");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }

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
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }

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
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }
        });
        event.preventDefault();
    });

    // Generic ajax-form
    $( "body" ).on('submit', '.ajax-form', function(event) {
        var action = $(this).attr("action");
        var form = $(this);
        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            var data = $.parseJSON(data);
            if (data['status'] == 1) {
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }
        });
        event.preventDefault();
    });

    // Pornstars paginator
    eventPaginatorPornstars = function () {
        $( ".site_pornstars_paginator .pagination" ).on('click', 'a', function(event) {
            var url = $(this).attr("href");

            $(".pornstars_ajax_container .pagination").append("<img src='/images/loading.gif'>").fadeIn('fast');

            $.ajax({
                url: url,
                method: 'get'
            }).done(function( data ) {
                $(".pornstars_ajax_container").html(data);
                eventPaginatorPornstars();
            });

            event.preventDefault();
        });

    }
    eventPaginatorPornstars();

    // Workers paginator
    eventPaginatorWorkers = function () {
        $( ".site_workers_paginator .pagination" ).on('click', 'a', function(event) {
            var url = $(this).attr("href");

            $(".workers_ajax_container .pagination").append("<img src='/images/loading.gif'>").fadeIn('fast');

            $.ajax({
                url: url,
                method: 'get'
            }).done(function( data ) {
                $(".workers_ajax_container").html(data);
                eventPaginatorWorkers();
            });

            event.preventDefault();
        });

    }
    eventPaginatorWorkers();

    //Categories paginator
    eventPaginatorCategories = function () {
        $( ".site_categories_paginator .pagination" ).on('click', 'a', function(event) {
            var url = $(this).attr("href");

            $(".categories_ajax_container .pagination").append("<img src='/images/loading.gif'>").fadeIn('fast');

            $.ajax({
                url: url,
                method: 'get'
            }).done(function( data ) {
                $(".categories_ajax_container").html(data);
                eventPaginatorCategories();
            });

            event.preventDefault();
        });

    }
    eventPaginatorCategories();

    //Tags paginator
    eventPaginatorTags = function () {
        $( ".site_tags_paginator .pagination" ).on('click', 'a', function(event) {
            var url = $(this).attr("href");

            $(".tags_ajax_container .pagination").append("<img src='/images/loading.gif'>").fadeIn('fast');

            $.ajax({
                url: url,
                method: 'get'
            }).done(function( data ) {
                $(".tags_ajax_container").html(data);
                eventPaginatorTags();
            });

            event.preventDefault();
        });

    }
    eventPaginatorTags();

    $( "body" ).on('click', '.delete-site-popunder-btn', function(event) {
        var action = $(this).attr("href");
        var site_container = $(this).parent().parent();
        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                site_container.remove();
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }
        });

        event.preventDefault();
    });

    $( ".category-search-form" ).submit(function( event ) {
        var url = $(this).attr("action");

        $.ajax({
            url: url,
            data: $(this).serialize(),
            method: 'get'
        }).done(function( data ) {
            $(".categories_ajax_container").html(data);
            eventPaginatorCategories();
            eventFileUpload();
        });

        event.preventDefault();
    });


    function eventUnlockCategories() {
        $( "body" ).on('click', '.btn-category-unlock', function(event) {
            var url = $(this).attr("href");
            var lock_container = $(this).parent().find('.locked');
            var btnunlock = $(this);

            $.ajax({
                url: url,
                method: 'get'
            }).done(function( data ) {

                jsonData = $.parseJSON(data);

                if (jsonData["status"] == true) {
                    lock_container.remove();
                    btnunlock.remove();
                    showGenericalSuccessMessage();
                } else {
                    showGenericalErrorMessage();
                }
            });

            event.preventDefault();
        });

    }
    eventUnlockCategories();

    $( "body" ).on('click', '.delete-site-cronjob-btn', function(event) {
        var action = $(this).attr("href");
        var site_container = $(this).parent().parent();
        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                showGenericalSuccessMessage();
                site_container.remove();
            } else {
                showGenericalErrorMessage();
            }
        });

        event.preventDefault();
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

    $("body").on('click', '.btn-change-category-thumbnail', function(event) {
        var action = $(this).attr("data-url");
        var category_container = $(this).parent().parent().find('.container-lock-action');

        $("#modal-sexodome .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#modal-sexodome .modal-body").html(data);

            $( ".category-thumb-image-selector" ).click(function() {
                var img = $(this).attr("src");
                var category_id = $(this).attr("data-category-id");

                $('.category-thumb-image-selector').css("border", "none");
                $(this).css("border", "solid 4px green");
                $('.category-form-'+category_id).find("input[name='thumbnail']").val(img);
                $('.category-form-'+category_id).find(".category-preview").attr('src', img);

                // Añadimos boton para desbloquear la thumbnail
                category_container.html('');
                var spanLocked = $('<span/>', {class: 'locked'});
                var icon = $('<i/>', {class: 'glyphicon glyphicon-cog'});
                var hrefUnlockButton = $('<a/>', {
                    href: category_container.attr('data-unlock-category-url'),
                    class: 'btn btn-success btn-xs btn-category-unlock'
                });
                hrefUnlockButton.text(' Unlock');
                icon.prependTo(hrefUnlockButton);
                $('<span class="locked"><i class="glyphicon glyphicon-ban-circle"></i> Thumbnail locked</span>').appendTo(category_container);
                spanLocked.appendTo(category_container);
                hrefUnlockButton.appendTo(category_container);
                eventUnlockCategories(); // De lo contrario no está enganchando el on.
            });

        });

        event.preventDefault();

    });

    $( ".seo-info-keywords" ).click(function() {
        var action = $(this).attr("data-url");

        $("#modal-sexodome .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#modal-sexodome .modal-body").html(data);
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

    $( "#selector_language" ).change(function() {
        var option = $(this).find('option:selected', this).attr('data-action');
        window.location = option;
    });

    $( "#selector_site" ).change(function() {
        var option = $(this).find('option:selected', this).attr('data-action');
        if (option) {
            window.location = option;
        }
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

// Sticker messages
function showGenericalErrorMessage(error = true) {
    $("#sticker").removeClass('sticker_ok');
    $("#sticker").addClass('sticker_ko');
    $("#sticker").find('.text-muted').html("<i class='glyphicon glyphicon-remove-sign'></i> Ooops some error has been encountered...");
    $("#sticker").fadeIn('slow').animate({opacity: 1.0}, 1500).effect("pulsate", { times: 2 }, 800).fadeOut('slow');
}

function showGenericalSuccessMessage() {
    console.log("entro");
    $("#sticker").removeClass('sticker_ko');
    $("#sticker").addClass('sticker_ok');
    $("#sticker").find('.text-muted').html("<i class='glyphicon glyphicon-ok-sign'></i> Operation done successfully");
    $("#sticker").fadeIn('slow').animate({opacity: 1.0}, 1500).effect("pulsate", { times: 2 }, 800).fadeOut('slow');
}

// Mantiene bien colocado el sticker que usamos para notificaciones.
function fixDiv() {
    var $cache = $('#sticker');
    if ($(window).scrollTop() > 25) {
        $cache.css({
            'position': 'fixed',
            'top': '0px'
        });
    }
}
// Vinculamos al evento scroll la recolocación del sticker de notificaciones
$(window).scroll(fixDiv);
fixDiv();
