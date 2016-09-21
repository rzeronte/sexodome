<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class SpankwireFeed
{
    //0 6258641|
    //1 " Slutty bimbo has her twat fucked"|
    //2 http://www.spankwire.com/Slutty-bimbo-has-her-twat-fucked/video6258641/|
    //3 1782|
    //4 50|
    //5 00:24:00|
    //6 http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/1.jpg|
    //7 cumshot;hairy;teen|
    //8 http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/1.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/2.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/3.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/4.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/5.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/6.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/7.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/8.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/9.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaeK81aAb/201609/17/6258641/originals/10.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/1.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/2.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/3.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/4.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/5.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/6.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/7.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/8.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/9.jpg;http://cdn4.image.spankwire.phncdn.com/m=eafT81aAb/201609/17/6258641/originals/10.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/1.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/2.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/3.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/4.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/5.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/6.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/7.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/8.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/9.jpg;http://cdn4.image.spankwire.phncdn.com/m=eag281aAb/201609/17/6258641/originals/10.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/1.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/2.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/3.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/4.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/5.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/6.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/7.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/8.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/9.jpg;http://cdn4.image.spankwire.phncdn.com/m=eayaasFnGw/201609/17/6258641/originals/10.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/1.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/2.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/3.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/4.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/5.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/6.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/7.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/8.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/9.jpg;http://cdn4.image.spankwire.phncdn.com/m=eVIL81aAb/201609/17/6258641/originals/10.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/1.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/2.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/3.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/4.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/5.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/6.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/7.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/8.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/9.jpg;http://cdn4.image.spankwire.phncdn.com/m=eXsG81aAb/201609/17/6258641/originals/10.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/1.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/2.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/3.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/4.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/5.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/6.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/7.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/8.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/9.jpg;http://cdn4.image.spankwire.phncdn.com/m=eaf881aAb/201609/17/6258641/originals/10.jpg

    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 2,
            "preview"    => 6,
            "thumbs"     => 8,
            "title"      => 1,
            "tags"       => 7,
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
            "totalCols"            => 9,
            "fields_separator"     => "|",
            "thumbs_separator"     => ";",
            "tags_separator"       => ";",
            "categories_separator" => ";",
            "pornstars_separator"  => ";",
            "skip_first_list"      => true,
            "is_xml"               => true,
            "parse_duration"       => function($string) {
                //hh:mm:ss format
                $values = explode(":", $string);
                $hour = intval($values[0]);
                $min = intval($values[1]);
                $sec = intval($values[2]);

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
            'video_id'      => $array_original['video']['id'],
            'title'         => utf8_encode($array_original['video']['title']),
            'url'           => $array_original['video']['url'],
            'views'         => $array_original['video']['views'],
            'rating'        => $array_original['video']['rating'],
            'duration'      => $array_original['video']['duration'],
            'thumb'         => $array_original['video']['thumb'],
            'tags'          => '',
            'thumbs'        => '',
        ];
        $thumbs = [];
        foreach($array_original['video']["thumbs"] as $thumb){
            $thumbs[] = $thumb['src'];
        }
        $csv_array["thumbs"] = implode(";", $thumbs);

        $tags = [];
        foreach($array_original['video']["tags"] as $tag){
            $tags[] = utf8_encode($tag);
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