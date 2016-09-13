<style>
    /*Layout*/
    body {
        background-color: {{$site->color}} !important;
    }

    .header{
        background-color: {{$site->color2}} !important;
    }

    .navbar-default {
        background-color: {{$site->color2}} !important;
    }

    .header .billboard{
        color: {{$site->color4}} !important;
    }

    .footer{
        background-color: {{$site->color3}} !important;
    }

    .footer a{
        color: {{$site->color4}};
    }

    .col-logo a{
        color: {{$site->color4}};
    }

    .input-search .btn{
        background-color: {{$site->color6}};
        color: {{$site->color7}} !important;
    }

    .btn-header-pornstars{
        background-color: {{$site->color6}};
        color: {{$site->color7}};
    }

    .btn-header-pornstars:hover{
        color: {{$site->color7}};
    }

    .btn-custom-user{
        background-color: {{$site->color6}};
        color: {{$site->color7}};
    }

    .btn-custom-user:hover{
        color: {{$site->color7}};
    }

    /****************************************************************************************** Categories*/
    .category_outer .link_image img {
        border: solid 0px {{$site->color2}};
    }

    .category_outer .category_info{
        background-color: {{$site->color2}} !important;
    }

    .category_outer .category_info .link_category {
        color: {{$site->color4}} !important;
    }

    .category_outer .category_info .link_nvideos {
        color: {{$site->color5}};
    }

    .header_title_section{
        border-bottom: solid 2px {{$site->color2}} !important;
        color: {{$site->color8}};
    }
    .header_title_section_mobile{
        color: {{$site->color8}};
    }

    /****************************************************************************************** Video*/
    .video_outer .link_image img {
        border: solid 0px {{$site->color2}};
    }

    .video_outer .info_video{
        background-color: {{$site->color2}} !important;
    }

    .video_outer .info_video .title {
        color: {{$site->color9}};
    }

    .video_outer .info_video .extra_info{
        color: {{$site->color10}} !important;
    }

    .video_outer .info_video .extra_info .channel_link {
        color: {{$site->color10}} !important;
    }

    .video_outer .info_video .category_link {
        background-color: {{$site->color6}};
        color: {{$site->color7}};
    }

    /****************************************************************************************** Pornstar*/
    .pornstar_outer .link_image img {
        border: solid 0px {{$site->color2}};
    }

    .pornstar_outer .pornstar_info {
        background-color: {{$site->color2}} !important;
    }

    .pornstar_outer .pornstar_info .pornstar_link{
        color: {{$site->color4}};
    }

    /****************************************************************************************** paginator */

    .pagination li a{
        background-color: {{$site->color2}};
        color: {{$site->color5}};
    }

    .pagination li a:hover{
        background-color: {{$site->color2}};
        color: {{$site->color5}};
    }

    .pagination .active > span {
        background-color: {{$site->color2}};
        color: {{$site->color5}};
    }

    .pagination .disabled > span {
        background-color: {{$site->color2}};
        color: {{$site->color5}};
    }

    .pagination .active > span:hover{
        background-color: {{$site->color2}};
        color: {{$site->color5}};
    }

    .pagination .disabled > span:hover{
        background-color: {{$site->color2}};
        color: {{$site->color5}};
    }

    .link_order{
        background-color: {{$site->color6}} !important;
        color: {{$site->color7}} !important;
    }

</style>