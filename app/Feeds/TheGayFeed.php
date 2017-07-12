<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class TheGayFeed
{

    //0 56922|
    //1 http://thegay.com/videos/56922/garments-pins-punching-kicking-ballbusting-part-1/|
    //2 garments pins punching kicking ballbusting, part 1|
    //3 I have met Slaver Wolf, who owns me fully (underneath villein-corporalist contract). Him and his partner Rebell are here playing with my bazookas and balls (and too my rectal hole from behind, but it's not visible on webcam): This is our 1st clip... This is part 1 of two...|
    //4 http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/13.jpg|
    //5 http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/1.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/2.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/3.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/4.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/5.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/6.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/7.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/8.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/9.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/10.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/11.jpg,http://direct.thegay.net/contents/videos_sources/56000/56922/screenshots/12.jpg,http://direct.thegay.net/contents/videos_sources/|
    //6 448|
    //7 26-08-2015|
    //8 Webcam,BDSM|
    //9 Balls,Boobs,Butthole,Couple,Doggystyle,Fetish,First Time,Kicking,Master,Slave,Webcam|
    //10 |
    //11 <iframe width="568" height="345" src="http://thegay.com/embed/56922" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>|


    //0 68335|
    //1 http://thegay.com/videos/68335/muscled-gay-dudes-in-a-shower/|
    //2 Muscled gay dudes in a shower|
    //3 There is no much space in a shower but these damn hot and horny muscled gay dude still have it enough to be able drilling each other's assholes intensively.|
    //4 http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/4.jpg|
    //5 http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/1.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/2.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/3.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/4.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/5.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/6.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/7.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/8.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/9.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/10.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/11.jpg,http://direct.thegay.net/contents/videos_sources/68000/68335/screenshots/12.jpg,http://direct.thegay.net/contents/videos_sources/|
    //6 1613|
    //7 01-06-2015|
    //8 Gay|
    //9Muscled,Shower|
    //10 |
    //11 <iframe width="568" height="345" src="http://thegay.com/embed/68335" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>|


    function mappingColumns()
    {
        $mapped_columns = array(
            "id"         => 0,
            "url"        => 1,
            "iframe"     => 11,
            "description"=> 3,
            "preview"    => 4,
            "thumbs"     => 5,
            "title"      => 2,
            "tags"       => 9,
            "categories" => 8,
            "duration"   => 6,
            "views"      => false,
            "likes"      => false,
            "unlikes"    => false,
            "pornstars"  => false,
        );

        return $mapped_columns;
    }

    function configFeed()
    {
        $feed_config = array(
            "totalCols"            => 13,
            "fields_separator"     => "|",
            "thumbs_separator"     => ",",
            "tags_separator"       => ",",
            "categories_separator" => ",",
            "pornstars_separator"  => ",",
            "skip_first_list"      => true,
            "parse_duration"       => function($string) {
                return $string;
            }
        );

        return $feed_config;
    }

    function configDeleteFeed() {
        $mapping = array(
            "type"      => false,
            "csv"       => false,
            "separator" => false,
            "index_url" => false,
        );

        return $mapping;
    }
}