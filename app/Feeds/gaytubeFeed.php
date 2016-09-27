<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class gaytubeFeed
{
    //0 92085|
    //1 "Naked guys dildo playing"|
    //2 http://www.gaytube.com/media/92085/naked_guys_dildo_playing/|
    //3 15656|
    //4 10|
    //5 02:38|
    //6 http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-1.jpg|
    //7 http://embed.gaytube.com/media/92085/naked_guys_dildo_playing/|
    //8 dude;naked;jerking;sex;nude;toy;wanking;ass;dildo;male;boy|
    //9 http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-1.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-2.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-3.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-4.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-5.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-6.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-7.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-8.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-9.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-10.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-1.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-2.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-3.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-4.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-5.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-6.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-7.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-8.jpg;http://cdn1.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-9.jpg;http://cdn2.image.gaytube.com/thumbs/2010-10-15/b31e39f442c71-10.jpg

    function mappingColumns()
    {
        $mapped_columns = array(
            "url"        => 2,
            "iframe"     => false,
            "preview"    => 6,
            "thumbs"     => 9,
            "title"      => 1,
            "tags"       => 8,
            "categories" => false,
            "duration"   => 5,
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
            "totalCols"            => 10,
            "fields_separator"     => "|",
            "thumbs_separator"     => ";",
            "tags_separator"       => ";",
            "categories_separator" => ";",
            "pornstars_separator"  => ";",
            "skip_first_list"      => true,
            "is_xml"               => true,
            "parse_duration"       => function($string) {
                //00m00s format
                $values = explode(":", $string);
                $min = intval($values[0]);
                $sec = intval($values[1]);

                return ($min*60)+$sec;
            }
        );

        return $feed_config;
    }

    function getVideosFromJSON($json)
    {
        return $json["videos"];
    }

    function getCSVLineFromJSON($array_original)
    {
        $csv_array = [
            'video_id'      => $array_original['video_id'],
            'title'         => utf8_encode($array_original['title']),
            'url'           => $array_original['url'],
            'views'         => $array_original['views'],
            'rating'        => $array_original['rating'],
            'duration'      => $array_original['duration'],
            'thumb'         => $array_original['thumb'],
            'embed_url'     => $array_original['embed_url'],
            'tags'          => '',
            'thumbs'        => '',
        ];
        $thumbs = [];
        foreach($array_original["thumbs"] as $thumb){
            $thumbs[] = $thumb['src'];
        }
        $csv_array["thumbs"] = implode(";", $thumbs);

        $tags = [];
        foreach($array_original["tags"] as $tag){
            $tags[] = utf8_encode($tag['tag_name']);
        }
        $csv_array["tags"] = implode(";", $tags);

        return $csv_array;
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