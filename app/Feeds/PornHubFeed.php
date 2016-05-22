<?php
namespace App\Feeds;

use App\Model\Host;
use App\Model\Video;

class PornHubFeed
{
    //0 <iframe src="http://www.pornhub.com/embed/0fcacce3976bf7c08af5" frameborder="0" height="481" width="608" scrolling="no"></iframe>|
    //1 http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/12.jpg|
    //2 http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/1.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/2.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/3.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/4.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/5.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/6.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/7.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/8.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/9.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/10.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/11.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/12.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/13.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/14.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/15.jpg;http://i0.cdn2b.image.pornhub.phncdn.com/m=eaf88daaaa/videos/200705/08/340/original/16.jpg|
    //3 Give it to me baby!
    //4 fat;big-ass;doggystyle;chubby;amateur;homemade;ebony|
    //5 Amateur;Ebony|
    //6 |
    //7 187|
    //8 608150|
    //9 426|
    //10 169

    function configFeed()
    {
        $feed_config = array(
            "totalCols"            => 11,
            "fields_separator"     => "|",
            "thumbs_separator"     => ";",
            "tags_separator"       => ";",
            "categories_separator" => ";",
            "skip_first_list"      => true
        );

        return $feed_config;
    }

    function mappingColumns()
    {
        $mapped_columns = array(
            "iframe"     => 0,
            "preview"    => 1,
            "thumbs"     => 2,
            "title"      => 3,
            "tags"       => 4,
            "categories" => 5,
            "duration"   => 7,
            "views"      => 8,
            "likes"      => 9,
            "unlikes"    => 10,
            "totalCols"  => 11
        );

        return $mapped_columns;
    }
}