<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\SceneClick;
use App\Model\SceneTranslation;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Scene;
use App\Model\Tag;
use App\Model\Host;
use App\Model\SceneTag;
use App\Model\TagClick;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use DB;

class rZeBotTweeter extends Command
{
    public $consumer_key;
    public $consumer_secret;
    public $access_token;
    public $access_token_secret;

    function __construct()
    {
        parent::__construct();

        $this->consumer_key = "VQx6KxjqVqI8y05LYrgYIQsjd";
        $this->consumer_secret = "HdxmPJad8MeT2oMkhUGMblqyWY7grkgrIKAjhJUZgdLxseebOG";
        $this->access_token = "4709109838-LMogh4L2ue2IlC31DftSLzXX0jxNJXAht81x1Em";
        $this->access_token_secret = "f5FW4IzQS3pdLQaCHhTfcdMT2jLu6FAzSBIEMB35dYwcg";
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:tweeter:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish tweeter';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $utils = new rZeBotUtils();

        echo date("Y-m-d H:i:s");
        
        $tweets = Tweet::where('status', 0)
            ->where('published_at', '<=', date("Y-m-d H:i:s"))
            ->get();

        foreach($tweets as $tweet) {
            echo PHP_EOL."Enviando Tweet para " . $tweet->published_at.PHP_EOL;
            $this->publishTweetWithImage($tweet->description, $tweet->scene->preview);
            $tweet->status = 1;
            $tweet->save();
        }

        $utils->message(PHP_EOL."".PHP_EOL.PHP_EOL, 'green');
    }

    public function publishTweetWithImage($message, $image)
    {
        $mediaData = $this->uploadMedia(base64_encode(file_get_contents($image)));
        $this->publishTweeter($message, $mediaData->media_id);

    }

    public function uploadMedia($file)
    {
        $settings= array(
            'consumer_key'              => $this->consumer_key,
            'consumer_secret'           => $this->consumer_secret,
            'oauth_access_token'        => $this->access_token,
            'oauth_access_token_secret' => $this->access_token_secret,
        );

        $url = 'https://upload.twitter.com/1.1/media/upload.json';
        $requestMethod = 'POST';

        $postfields = array(
            'media' => $file
        );

        $twitter = new TwitterAPIExchange($settings);

        $result = $twitter->buildOauth($url, $requestMethod)
            ->setPostfields($postfields)
            ->performRequest();

        return json_decode($result);
    }

    public function publishTweeter($message, $media_id)
    {
        $settings= array(
            'consumer_key'              => $this->consumer_key,
            'consumer_secret'           => $this->consumer_secret,
            'oauth_access_token'        => $this->access_token,
            'oauth_access_token_secret' => $this->access_token_secret,
        );

        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        $requestMethod = 'POST';

        $postfields = array(
            'status'    => $message,
            'media_ids' => $media_id
        );

        $twitter = new TwitterAPIExchange($settings);

        $result = $twitter->buildOauth($url, $requestMethod)
            ->setPostfields($postfields)
            ->performRequest();

        return json_decode($result);
    }
}
