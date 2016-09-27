<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class HClipsFeed
{
    //0 4791|
    //1 http://www.hclips.com/videos/trying-to-make-my-dick-hard/|
    //2 Trying to make my dick hard|
    //3 Beverly is trying to make my dick hard. As you can see, she's doing a good job at it. It can take a while to get me worked up. Beverly doesn't mind one bit. She likes to tug on my cock. It makes her day. I don't know if that is true or not. It does make my day. I keep telling her that one of these days she's going to make my cock swell so big it will explode. That always makes her laugh.|
    //4 http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/1.jpg|
    //5 http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/1.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/2.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/3.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/4.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/5.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/6.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/7.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/8.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/9.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/10.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/11.jpg,http://direct.hclips.com/contents/videos_sources/4000/4791/screenshots/12.jpg|
    //6 293|
    //7 03-01-2008|
    //8 Amateur,Straight|
    //9 NULL|
    //10 <iframe width="568" height="345" src="http://www.hclips.com/embed/4791" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>|
    //11

    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 10,
            "preview"    => 4,
            "thumbs"     => 5,
            "title"      => 2,
            "description"=> 3,
            "tags"       => 8,
            "categories" => false,
            "duration"   => 6,
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