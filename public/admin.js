var timer;

$( document ).ready(function() {

    function eventFileUpload() {
        $('.fileupload').fileupload({
            dataType: 'json',
            done: function (e, data) {

                $.each(data.result.files, function (index, file) {
                    var category_id = file.category_id;
                    var url = file.url;
                    $('.category-form-'+category_id).find("input[name='thumbnail']").val(url);
                    $('.category-form-'+category_id).find(".category-preview").attr('src', url);

                    var category_container = $('.category-form-'+category_id).find('.container-lock-action');

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

                    showGenericalSuccessMessage();
                });

                if (data.result.files.length == 0) {
                    showGenericalErrorMessage();
                    return;
                }

            }
        });
    }
    eventFileUpload();

    function eventFileSiteLogoUpload() {
        $('.fileuploadSiteLogo').fileupload({
            dataType: 'json',
            done: function (e, data) {
                if (data.result.status == false) {
                    showGenericalErrorMessage();
                    return;
                } else {
                    $.each(data.result.files, function (index, file) {
                        if (file.logo_url) {
                            $("#site_logo_image").attr('src', file.logo_url)
                        } else if (file.favicon_url) {
                            $("#site_favicon_image").attr('src', file.favicon_url)
                        } else if (file.header_url) {
                            $("#site_header_image").attr('src', file.header_url)
                        }
                    });
                    showGenericalSuccessMessage();
                }
            }
        });
    }
    eventFileSiteLogoUpload();

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
            if (data['status'] == true) {
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }
        });
        event.preventDefault();
    });

    // Submit del formulario de creación de una categoría
    $( "body" ).on('submit', '.form-create-category', function(event) {
        var action = $(this).attr("action");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            var data = $.parseJSON(data);
            if (data['status'] == true) {
                $('#modal-sexodome').modal('hide')
                showGenericalSuccessMessage();
            } else {
                $('#modal-sexodome').modal('hide')
                showGenericalErrorMessage();
            }
        });
        event.preventDefault();
    });

    // Submit del formulario de creación de un tag
    $( "body" ).on('submit', '.form-create-tag', function(event) {
        var action = $(this).attr("action");
        var form = $(this);

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            var data = $.parseJSON(data);
            if (data['status'] == true) {
                $('#modal-sexodome').modal('hide')
                showGenericalSuccessMessage();
            } else {
                $('#modal-sexodome').modal('hide')
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
                $("html, body").animate({ scrollTop: 0 }, "fast");
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
                $("html, body").animate({ scrollTop: 0 }, "fast");
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
                eventFileUpload();

                $("html, body").animate({ scrollTop: 0 }, "fast");

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
                $("html, body").animate({ scrollTop: 0 }, "fast");

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

    $( ".tag-search-form" ).submit(function( event ) {
        var url = $(this).attr("action");

        $.ajax({
            url: url,
            data: $(this).serialize(),
            method: 'get'
        }).done(function( data ) {
            $(".tags_ajax_container").html(data);
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

    $( "body" ).on('click', '.btn-delete-scene', function(event) {
        var action = $(this).attr("href");
        var site_container = $(this).parent().parent().parent();
        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                showGenericalSuccessMessage();
                site_container.slideUp().remove();
            } else {
                showGenericalErrorMessage();
            }
        });

        event.preventDefault();
    });

    $( "body" ).on('click', '.btn-delete-category', function(event) {
        var action = $(this).attr("href");
        var site_container = $(this).parent().parent().parent();
        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                showGenericalSuccessMessage();
                site_container.slideUp().remove();
            } else {
                showGenericalErrorMessage();
            }
        });

        event.preventDefault();
    });

    $( "body" ).on('click', '.btn-delete-tag', function(event) {
        var action = $(this).attr("href");
        var site_container = $(this).parent().parent().parent();
        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                showGenericalSuccessMessage();
                site_container.slideUp().remove();
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

                $('#modal-sexodome').modal('hide')

                eventUnlockCategories(); // De lo contrario no está enganchando el on.
            });

        });

        event.preventDefault();

    });

    $("body").on('click', '.btn-change-category-tags', function(event) {
        var action = $(this).attr("data-url");

        $("#modal-sexodome .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#modal-sexodome .modal-body").html(data);
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

    // Saca el dialogo con el formulario para crear una categoría
    $( ".btn-create-category" ).click(function() {
        var action = $(this).attr("data-url");

        $("#modal-sexodome .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#modal-sexodome .modal-body").html(data);
        });
    });

    // Saca el dialogo con el formulario para crear un tag
    $( ".btn-create-tag" ).click(function() {
        var action = $(this).attr("data-url");

        $("#modal-sexodome .modal-body").html("Loading...");

        $.ajax({
            url: action,
            method: 'get'
        }).done(function( data ) {
            $("#modal-sexodome .modal-body").html(data);
        });
    });


    $( ".btn-update-categories-order" ).click(function(e) {
        var action = $(this).attr("href");
        var arrayCategories = [];

        var i = 1;
        $('ul#sortable li').each( function( i ) {
            arrayCategory = {
                'i': $(this).attr('data-category-id'),
                'o': i
            };
            i++;

            arrayCategories.push(arrayCategory);
        });

        $.ajax({
            url: action,
            method: 'get',
            data: {'o': arrayCategories},
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }
        });

        e.preventDefault();
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
function showGenericalErrorMessage() {
    $("#sticker").removeClass('sticker_ok');
    $("#sticker").addClass('sticker_ko');
    $("#sticker").find('.text-muted').html("<i class='glyphicon glyphicon-remove-sign'></i> Ooops some error has been encountered...");
    $("#sticker").fadeIn('slow').animate({opacity: 1.0}, 1500).effect("pulsate", { times: 2 }, 800).fadeOut('slow');
}

function showGenericalSuccessMessage() {
    $("#sticker").show();
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
