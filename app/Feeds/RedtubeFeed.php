<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class RedtubeFeed
{
    //#ID|#THUMB|#URL|#TITLE|#CHANNEL|#TAG|#PORNSTAR|#DURATION|#DATE|

    //0 527891|
    //1 http://img.l3.cdn.redtubefiles.com/_thumbs/0000527/0527891/0527891_002s.jpg;http://img.l3.cdn.redtubefiles.com/_thumbs/0000527/0527891/0527891_003s.jpg;http://img.l3.cdn.redtubefiles.com/_thumbs/0000527/0527891/0527891_004s.jpg;http://img.l3.cdn.redtubefiles.com/_thumbs/0000527/0527891/0527891_005s.jpg;http://img.l3.cdn.redtubefiles.com/_thumbs/0000527/0527891/0527891_006s.jpg|
    //2 http://www.redtube.com/527891|
    //3 dakoda brookes dp|
    //4 Anal;Group;Facials;Double Penetration;Lingerie|
    //5 Vaginal Sex;Masturbation;Oral Sex;Anal Sex;Double Penetration;Brunette;Small Tits;Caucasian;Vaginal Masturbation;Anal Masturbation;Blowjob;Shaved;Tattoos;Deepthroat;Pornstar;Stockings;Lingerie;Cum Shot;Threesome;Facial;High Heels|
    //6 Dakoda Brookes|
    //7 41m57s|
    //8 2016-08-23|

    function mappingColumns()
    {
        $mapped_columns = array(
            "url"        => 2,
            "iframe"     => false,
            "preview"    => false,
            "thumbs"     => 1,
            "title"      => 3,
            "tags"       => 5,
            "categories" => 4,
            "pornstars"  => 6,
            "duration"   => 7,
            "views"      => false,
            "likes"      => false,
            "unlikes"    => false,
        );

        return $mapped_columns;
    }

    function configFeed()
    {
        $feed_config = array(
            "totalCols"            => 10,
            "fields_separator"     => "|",
            "thumbs_separator"     => ";",
            "tags_separator"       => ";",
            "categories_separator" => ";",
            "pornstars_separator"  => ";",
            "skip_first_list"      => true,
            "parse_duration"       => function($string) {
                //00m00s format
                $values = explode("m", $string);
                $min = intval($values[0]);
                $sec = intval($values[1]);

                return ($min*60)+$sec;
            }
        );

        return $feed_config;
    }

    function configDeleteFeed() {
        $mapping = array(
            "csv"       => true,
            "separator" => "|",
            "index_url" => 1,
        );

        return $mapping;
    }
}