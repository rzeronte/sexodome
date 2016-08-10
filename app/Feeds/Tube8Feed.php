<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class Tube8Feed
{

    //0 http://www.tube8.com/latina/evilangel-latina-pov-anal-fucked-and-shows-rosebud/25371911/|
    //1 Latina|
    //2 100.00|
    //3 EvilAngel Latina POV Anal Fucked and Shows Rosebud|
    //4 anal;ass-fuck;big-dick;colombian;cum-eating;cumshot;evilangel;gape;hairy;high-heels;latin;latina;open-mouth-cumshot;outdoor;point-of-view;pov;rosebud;|
    //5 440|
    //6 |
    //7 http://cdn1e.image.tube8.phncdn.com/m=eGcE8haaaa/201505/13/25371911/originals/8.jpg

    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 0,
            "categories" => 1,
            "preview"    => 7,
            "thumbs"     => false,
            "title"      => 3,
            "tags"       => 4,
            "duration"   => 5,
            "views"      => false,
            "likes"      => false,
            "unlikes"    => false,
        );

        return $mapped_columns;
    }

    function configFeed()
    {
        $feed_config = array(
            "totalCols"            => 8,
            "fields_separator"     => "|",
            "thumbs_separator"     => ";",
            "tags_separator"       => ";",
            "categories_separator" => ";",
            "pornstars_separator"  => ";",
            "skip_first_list"      => true,
            "parse_duration"       => function($string) {
                return $string;
            }
        );

        return $feed_config;
    }

}