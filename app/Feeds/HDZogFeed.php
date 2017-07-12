<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class HDZogFeed
{
    //0 3955|
    //1 http://www.hdzog.com/videos/3955/jmac-s-fat-cock-meets-stella-on-a-scooter/|
    //2 Jmac's fat cock meets Stella on a scooter|
    //3 This week Money Talks is back starring Nicole Aniston in Dildo Mask. Watch as we find someone in the streets to come back to our studio and fuck Nicole with the DIldo Mask! Then we head back to the world famous Viper Room for a good ole Best Ass Contest. Finally we are back at the scooter store with Jmac where we meet sexy Stella. She is gonna leave you wanting more!! Check it out|
    //4 http://direct.hdzog.com/contents/videos_sources/3000/3955/screenshots/1.jpg|
    //5 http://direct.hdzog.com/contents/videos_sources/3000/3955/screenshots/1.jpg,http://direct.hdzog.com/contents/videos_sources/3000/3955/screenshots/2.jpg,http://direct.hdzog.com/contents/videos_sources/3000/3955/screenshots/3.jpg,http://direct.hdzog.com/contents/videos_sources/3000/3955/screenshots/4.jpg,http://direct.hdzog.com/contents/videos_sources/3000/3955/screenshots/5.jpg|
    //6 600|
    //7 28-04-2014|
    //8 Big Tits,Teen,Blonde,Dildos/Toys,Reality,Hardcore,Skinny,HD|
    //9 Nicole Aniston,money talks|
    //10 Nicole Aniston|
    //11 <iframe width="1280" height="720" src="http://www.hdzog.com/embed/3955" frameborder="0" allowfullscreen="" webkitallowfullscreen="" mozallowfullscreen="" oallowfullscreen="" msallowfullscreen=""></iframe>|


    function mappingColumns()
    {
        $mapped_columns = array(
            "id"         => 0,
            "url"        => 1,
            "iframe"     => 11,
            "preview"    => 4,
            "thumbs"     => 5,
            "title"      => 2,
            "tags"       => false,
            "categories" => 8,
            "pornstars"  => 10,
            "duration"   => 6,
            "views"      => false,
            "likes"      => false,
            "unlikes"    => false,
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
            "type"      => "id",
            "csv"       => false,
            "separator" => false,
            "index_url" => false,
        );

        return $mapping;
    }
}