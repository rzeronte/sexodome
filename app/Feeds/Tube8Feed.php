<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class Tube8Feed
{

    //0 http://www.tube8.com/hardcore/ebony-bitch-filled-with-two-cocks-w-ana-foxxx/30453481/|
    //1 Hardcore
    //2 100.00
    //3 651digger
    //4 Ebony Bitch Filled With Two Cocks w Ana Foxxx|
    //5 threesome;shaved;small-tits;ebony;big-cock;sucking;blowjob;deepthroat;rough;domination;rimming;anal;double-penetration;cumshot;facial|
    //6 2119|
    //7 |
    //8 http://cdn2e.image.tube8.phncdn.com/m=eGcE8haaaa/201606/09/30453481/originals/6.jpg|
    //9 http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/1.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/2.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/3.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/4.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/5.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/6.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/7.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/8.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/9.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/10.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/11.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/12.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/13.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/14.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/15.jpg;http://cdn2e.image.tube8.phncdn.com/201606/09/30453481/160x120/16.jpg


    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 0,
            "categories" => 1,
            "preview"    => 8,
            "thumbs"     => 9,
            "title"      => 4,
            "tags"       => 5,
            "duration"   => 6,
            "views"      => false,
            "likes"      => false,
            "unlikes"    => false,
            "pornstars"  => 7,
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
                return $string;
            }
        );

        return $feed_config;
    }

    function configDeleteFeed() {
        $mapping = array(
            "csv"       => false,
            "separator" => false,
            "index_url" => false,
        );

        return $mapping;
    }
}