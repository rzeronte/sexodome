/* 1 Color Body Bg */
body {
    background-color: {{$site->color}} !important;
}
.thumbnail {
    background-color: {{$site->color}} !important;
}

/* 2 Color Header Bg */
.navbar-default
{
    background-color: {{$site->color2}} !important;

    @if (file_exists(\App\rZeBot\rZeBotCommons::getHeadersFolder()."/".md5($site->id).".png"))
    background-image: url('{{asset('/headers/'.md5($site->id).".png")}}');
    background-position: top left;
    background-size: 100%;
    background-repeat: no-repeat;
    @endif
}

/* 3 Color Header Right Text */
.navbar h4
{
    color: {{$site->color3}} !important;
}

/* 4 Color Billboard bg */
/* 5 Color Text Billboard */
.billboard{
    background-color: {{$site->color4}} !important;
    color: {{$site->color5}} !important;
}

.billboard a{
    background-color: {{$site->color4}} !important;
    color: {{$site->color5}} !important;
}

/* 6 Color Section Text */
/* 7 Color Section Text Secondary */
.page-header h2{
    color: {{$site->color6}} !important;
    font-size: 1.5em;
    color: #fff;
    padding: 0;
    margin: 0;
}

.page-header h3{
    color: {{$site->color6}} !important;
    font-size: 1.5em;
    color: #fff;
    padding: 0;
    margin: 0;
}

.page-header .mdi{
    color: {{$site->color6}} !important;
}

.page-header h2 small{
    color: {{$site->color7}} !important;
}

.scene-description{
    font-size: 18px;
    color: {{$site->color8}} !important;
}

/* 8 Color Video Text */
.main .thumbnail a h5{
    color: {{$site->color8}} !important;
}

/* 9 Color Tag */
.main .thumbnail .list-inline li .label {
    background-color: {{$site->color9}} !important;
    color: transparent;
}

/* 10 Buttons BG */
/* 11 Buttons text */
.main .pagination li a{
    background-color: {{$site->color10}} !important;
    color: {{$site->color11}} !important;
}

.navbar .btn-default{
    background-color: {{$site->color10}} !important;
    color: {{$site->color11}} !important;
}

.page-header .text-right .btn-secondary.active {
    background-color: {{$site->color10}} !important;
    border-color: {{$site->color10}} !important;
    color: {{$site->color11}} !important;
}

/* 12 On Video BG */
.main .thumbnail .thumb-image .floater-b-c,
.main .thumbnail .thumb-image .floater-b-l,
.main .thumbnail .thumb-image .floater-t-l {
    background-color: {{$site->color12}} !important;
    opacity: 0.7;
}

/* Fixes */
.main .thumbnail .thumb-image img{
    height: 100%;
}

