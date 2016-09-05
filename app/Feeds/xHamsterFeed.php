<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class xHamsterFeed
{
    //0 6090168|
    //1 http://et08.xhcdn.com/t/168/8_6090168.jpg;http://et08.xhcdn.com/t/168/1_6090168.jpg;http://et08.xhcdn.com/t/168/2_6090168.jpg;http://et08.xhcdn.com/t/168/3_6090168.jpg;http://et08.xhcdn.com/t/168/4_6090168.jpg|
    //2 http://xhamster.com/movies/6090168/mischievous_kari_k_fucked.html|
    //3 Mischievous Kari K Fucked|
    //4 Czech;Hardcore;Small Tits;Teens|
    //5 8m3s|
    //6 -> vacía

    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 2,
            "preview"    => false,
            "thumbs"     => 1,
            "title"      => 3,
            "tags"       => 4,
            "categories" => false,
            "duration"   => 5,
            "views"      => false,
            "likes"      => false,
            "unlikes"    => false,
            "totalCols"  => false,
            "pornstars" => false,
        );

        return $mapped_columns;
    }

    function configFeed()
    {
        $feed_config = array(
            "totalCols"            => 7,
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
            "csv"       => false,
            "separator" => false,
            "index_url" => false,
        );

        return $mapping;
    }

}