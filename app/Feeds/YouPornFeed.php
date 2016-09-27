<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class YouPornFeed
{
    //0 "<iframe src='http://www.youporn.com/embed/12707645/throating-my-wife-with-this-tattooed-cock/' frameborder='0' height='481' width='608' scrolling='no' name='yp_embed_video'><a href='http://www.youporn.com/embed/12707645/throating-my-wife-with-this-tattooed-cock/'>throating my wife with this tattooed cock</a> powered by <a href='http://www.youporn.com'>YouPorn</a>.</iframe>"|
    //1 http://www.youporn.com/watch/12707645/throating-my-wife-with-this-tattooed-cock/|
    //2 |
    //3 100|
    //4 emandj420|
    //5 "throating my wife with this tattooed cock"|
    //6 big-cock;big-tattooed-cock-bj;butt;cock-throating;point-of-view|
    //7 419|
    //8 |
    //9 http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/8.jpg|
    //10 http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/1.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/2.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/3.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/4.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/5.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/6.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/7.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/8.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/9.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/10.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/11.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/12.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/13.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/14.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/15.jpg;http://cdnph1.image.youporn.phncdn.com/m=eGcEKe/videos/201604/24/74789541/original/16.jpg

    function mappingColumns()
    {
        $mapped_columns = array(
            "url"        => 1,
            "iframe"     => 0,
            "preview"    => 9,
            "thumbs"     => 10,
            "title"      => 5,
            "tags"       => 6,
            "categories" => false,
            "duration"   => 7,
            "views"      => 3,
            "likes"      => false,
            "unlikes"    => false,
            "pornstars"  => false,
        );

        return $mapped_columns;
    }

    function configFeed()
    {
        $feed_config = array(
            "totalCols"            => 11,
            "fields_separator"     => "|",
            "thumbs_separator"     => ";",
            "tags_separator"       => ";",
            "categories_separator" => ",",
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