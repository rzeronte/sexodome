<style>
    body{
        background-color: {{$site->color12}} !important;
    }

    .header {
        background-color: {{$site->color4}} !important;
    }

    .header .header_col_billboard{
        color: {{$site->color10}} !important;
    }

    .header .header_query_string button {
        background-color: {{$site->color}} !important;
        color: white !important;
        border: solid 1px {{$site->color2}} !important;
    }

    .header .header_query_string input{
        background-color: {{$site->color}} !important;
        color: white !important;
        border: solid 1px {{$site->color2}} !important;
    }

    #query_string{
        color: {{$site->color2}} !important;
    }

    /******************************************************************** Tags */
    .header_menu .btn{
        background-color: {{$site->color}} !important;
        color: {{$site->color2}} !important;
    }

    .tags_header .tag{
        color: {{$site->color2}} !important;
        background-color: {{$site->color}} !important;
    }

    .tags_index{
        background-color: {{$site->color}}  !important;
    }

    /******************************************************************** Videos */

    .post_title{
        background-color: {{$site->color7}} !important;
        color: {{$site->color5}} !important;
    }

    .tubethumbnail:hover{
        border: solid 4px {{$site->color2}};
        box-sizing: border-box;
    }

    .tubethumbnail .post_title:hover{
        color: {{$site->color6}} !important;
    }

    .videos .fleft{
        background-color: {{$site->color3}};
        color: {{$site->color11}} !important;
    }

    .link_category{
        color: {{$site->color8}} !important;
        background-color: {{$site->color9}} !important;
    }

    .video_post .inner .screencast .media-length {
        color: {{$site->color2}};
        background-color: {{$site->color}} !important;
    }
    /******************************************************************** Pagination */
    .pagination a {
        border: solid 1px {{$site->color2}} !important;
        color: {{$site->color11}} !important;
        background-color: {{$site->color}} !important;
    }

    .pagination a:hover,
    .pagination .active a {
        border: solid 1px {{$site->color2}} !important;
        background-color: {{$site->color}} !important;
        color: {{$site->color11}} !important;
    }

    .pagination .active a {
        border: solid 1px {{$site->color2}} !important;
        color: {{$site->color}};
        background-color: {{$site->color11}} !important;
    }

    .pagination .active span{
        border: solid 1px {{$site->color2}} !important;
        background-color: {{$site->color}} !important;
    }

    .pagination .disabled span,
    .pagination .disabled a,
    .pagination .disabled a:hover {
        color: {{$site->color11}};
        background-color: {{$site->color}} !important;
    }

    .pagination li:first-child a {
        color: {{$site->color11}} !important;
    }

    .pagination li:last-child a {
        color: {{$site->color11}} !important;
    }

    .pagination .disabled span{
        border-color: {{$site->color2}} !important;
        color: {{$site->color11}} !important;
    }

    /******************************************************************** Video Duration */

    .media-length{
        background-color: {{$site->color}} !important;
    }

    /******************************************************************** Thumbnail Detail */
    .show-video h2{
        color: {{$site->color11}} !important;
    }
    /******************************************************************** */

    footer{
        background-color: {{$site->color}} !important;
    }

    .tag-video{
        background-color: {{$site->color8}} !important;
        color: {{$site->color9}};
    }

    .tag-category{
        background-color: {{$site->color8}} !important;
        color: {{$site->color9}};
    }

    .channel_link{
        color: {{$site->color8}} !important;
        background-color: {{$site->color9}} !important;
    }

    /*Categorias*/
    .tube_cat .text_link a {
        color: {{$site->color11}} !important;
    }

    .tube_cat .img_link{
        border: solid 1px {{$site->color2}} !important;
    }

    .category_header{
        color: {{$site->color11}} !important;
    }

    .scene_extra_info{
        color: {{$site->color6}} !important;
    }
</style>
