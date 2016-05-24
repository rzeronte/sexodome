<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class YouPornFeed
{
    //    [0] => EMBEDIFRAMECODE
    //    [1] => THUMB
    //    [2] => TITLE
    //    [3] => TAG
    //    [4] => CATEGORY
    //    [5] => PORNSTAR
    //    [6] => DURATION

    //0 "<iframe src='http://www.youporn.com/embed/910/real-rubber-doll/' frameborder='0' height='481' width='608' scrolling='no' name='yp_embed_video'><a href='http://www.youporn.com/watch/910/real-rubber-doll/'>Real rubber doll</a> powered by <a href='http://www.youporn.com'>YouPorn</a>.</iframe><br /><a href='http://www.youporn.com/watch/910/real-rubber-doll/'>Real rubber doll</a> powered by <a href='http://www.youporn.com'>YouPorn</a>."
    //1 http://cdn4.image.youporn.phncdn.com/200609/25/910/original/1.jpg?m=eSuQKe,http://cdn5.image.youporn.phncdn.com/200609/25/910/original/2.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/3.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/4.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/5.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/6.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/7.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/8.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/9.jpg?m=eSuQKe,http://cdn5.image.youporn.phncdn.com/200609/25/910/original/10.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/11.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/12.jpg?m=eSuQKe,http://cdn5.image.youporn.phncdn.com/200609/25/910/original/13.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/14.jpg?m=eSuQKe,http://cdn5.image.youporn.phncdn.com/200609/25/910/original/15.jpg?m=eSuQKe,http://cdn4.image.youporn.phncdn.com/200609/25/910/original/16.jpg?m=eSuQKe|
    //2 "Real rubber doll"|
    //3 |
    //4 Fetish,Fantasy,Dildos/Toys|
    //5 |
    //6 05m15s


    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 0,
            "preview"    => false,
            "thumbs"     => 1,
            "title"      => 2,
            "tags"       => 3,
            "categories" => 4,
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
            "totalCols"            => 7,
            "fields_separator"     => "|",
            "thumbs_separator"     => ",",
            "tags_separator"       => ";",
            "categories_separator" => ",",
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

}