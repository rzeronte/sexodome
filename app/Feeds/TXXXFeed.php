<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class TXXXFeed
{
    //0 127448|
    //1 http://www.txxx.com/videos/127448/two-arab-gals-from-syria-two/|
    //2 two Arab gals from Syria two|
    //3 |
    //4 http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/1.jpg|
    //5 http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/1.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/2.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/3.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/4.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/5.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/6.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/7.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/8.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/9.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/10.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/11.jpg,http://direct.txxx.com/contents/videos_sources/127000/127448/screenshots/12.jpg|
    //6 775|
    //7 17-01-2012|
    //8 Arab|
    //9|
    //10 <iframe width="568" height="345" src="http://www.txxx.com/embed/127448" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>|

    //0 126965|
    //1 http://www.txxx.com/videos/126965/arab-bitch-arse-drilled-and-double-penetration-ed/|
    //2 Arab Bitch Arse Drilled and double penetration'ed|
    //3 A cute European Arab gal with short hair, a bald cookie and good marangos, sucks ramrod, acquires her butt drilled, receives double penetration'ed and takes multiple loads of nut from two white boys. Have A Fun!|
    //4 http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/1.jpg|
    //5 http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/1.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/2.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/3.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/4.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/5.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/6.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/7.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/8.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/9.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/10.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/11.jpg,http://direct.txxx.com/contents/videos_sources/126000/126965/screenshots/12.jpg|
    //6 761|
    //7 10-01-2012|
    //8 Arab|
    //9 |
    //10 <iframe width="568" height="345" src="http://www.txxx.com/embed/126965" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>|

    function mappingColumns()
    {
        $mapped_columns = array(
            "url"         => 1,
            "title"       => 2,
            "description" => 3,
            "preview"     => 4,
            "thumbs"      => 5,
            "duration"    => 6,
            "tags"        => 8,
            "iframe"      => 9,
            "categories"  => false,
            "views"       => false,
            "likes"       => false,
            "unlikes"     => false,
            "pornstars"   => false,
        );

        return $mapped_columns;
    }

    function configFeed()
    {
        $feed_config = array(
            "totalCols"            => 12,
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
            "csv"       => false,
            "separator" => false,
            "index_url" => false,
        );

        return $mapping;
    }
}