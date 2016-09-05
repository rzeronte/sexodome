<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class PornHubFeed
{
    //0 "<iframe src=""http://www.pornhub.com/embed/ph57a9ad995d042"" frameborder=""0"" width=""608"" height=""338"" scrolling=""no""></iframe>"|
    //1 "http://www.pornhub.com/view_video.php?viewkey=ph57a9ad995d042"|
    //2 "Amateur;Big Dick;Blowjob;POV"|
    //3 "0"|
    //4 "ramzxl1234"|
    //5 "Getting some head"|
    //6 "big-cock;point-of-view"|
    //7 "109"|
    //8 ""|
    //9 "http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/12.jpg"|
    //10 "http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/1.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/2.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/3.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/4.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/5.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/6.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/7.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/8.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/9.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/10.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/11.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/12.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/13.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/14.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/15.jpg;http://i1.cdn2a.image.pornhub.phncdn.com/m=eGcE8daaaa/videos/201608/09/85131451/original/16.jpg"

    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 1,
            "preview"    => 9,
            "thumbs"     => 10,
            "title"      => 5,
            "tags"       => 6,
            "categories" => 2,
            "duration"   => 7,
            "views"      => 8,
            "likes"      => false,
            "unlikes"    => 10,
            "pornstars"  => 8,
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