<?php

namespace App\Http\Controllers;

use App;
use DB;
use Request;
use Validator;
use Input;
use Session;
use URL;
use App\Model\Host;
use App\Model\Video;
use App\Model\Scene;
use App\Model\SceneTranslation;
use App\Model\SceneClick;
use App\Model\SceneTag;
use App\Model\Tag;
use App\Model\TagTranslation;
use App\Model\Origin;
use App\Model\Language;
use App\Model\LanguageTag;
use App\Model\TagClick;
use App\Model\Tweet;
use App\Model\Zone;
use App\Model\Ad;
use App\Model\Site;
use App\Model\Logpublish;
use App\rZeBot\rZeBotUtils;
use Illuminate\Support\Facades\Route;

class ConfigController extends Controller
{
    private $language;
    private $languages;
    private $locale;
    private $perPageScenes;
    private $sites;

    public function __construct()
    {
        $locale = Route::current()->getParameter('locale', "es");

        // set locale
        $this->locale = $locale;

        // current language
        $this->language = Language::where('code', 'like', $locale)->first();

        // all valid languages
        $this->languages = Language::where('status', 1)->get();

        // results per page
        $this->perPageTags = 50;
        $this->perPageScenes = 6;

        // sites
        $this->sites = array(
            ['name' => 'assassinsporn',   'tags' => []],
            ['name' => 'mamasfollando',   'tags' => ['milf', 'mom', 'mother', 'wife', 'mature', 'older', 'milfs']],
            ['name' => 'latinasparadise', 'tags' => ['latina', 'italian', 'latinas', 'latino','spanish', 'peruvian', 'colombian', 'cuba', 'cuban', 'brazil']],
            ['name' => 'dirtyblow',       'tags' => ['extreme', 'bdsm', 'masochism', 'bondage', 'unusual', 'fetish', 'shemale', 'tied']],
        );
    }

    public function home()
    {
        return redirect()->route('content', ['locale' => "es"]);
    }

    public function index($locale)
    {
        $query_string = Request::get('q');
        $tag_query_string = Request::get('tag_q');
        $publish_for = Request::get('publish_for');

        $remote_scenes = [];
        if ($publish_for) {
            $remote_scenes = Scene::getRemoteSceneIdsFor($publish_for);
        }

        $scenes = Scene::getScenesForExporterSearch(
            $query_string,
            $tag_query_string,
            $remote_scenes,
            $this->language->id
        );

        return view('index', [
            'scenes'       => $scenes->paginate($this->perPageScenes),
            'total_scenes' => $scenes->count(),
            'query_string' => $query_string,
            'tag_q'        => $tag_query_string,
            'publish_for'  => $publish_for,
            'language'     => $this->language,
            'languages'    => $this->languages,
            'locale'       => $this->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->sites
        ]);
    }

    public function tags($locale)
    {
        $query_string = Request::get('q');
        $tags = Tag::getTranslationSearch($query_string, $this->language->id);
        $tag_query_string = Request::get('tag_q');

        return view('tags', [
            'tags'       => $tags->paginate($this->perPageTags),
            'query_string' => $query_string,
            'tag_q'        => $tag_query_string,
            'language'     => $this->language,
            'languages'    => $this->languages,
            'locale'       => $this->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->sites
        ]);

    }

    public function changeLocale($locale)
    {
        App::setLocale($locale);

        return redirect()->route('content', ['locale' => $this->locale]);
    }

    public function exportScene($locale, $scene_id)
    {
        $scene = Scene::find($scene_id);
        $database = Request::input('database');

        $logdatabase = $scene->logspublish()->where('site', $database)->count();

        if ($logdatabase == 0) {
            $log = new Logpublish();
            $log->scene_id = $scene->id;
            $log->site = $database;
            $log->save();
        }

        $languages = Language::all();

        $sql = "SELECT * FROM scenes WHERE id = ".$scene->id;
        $domain_scene = DB::connection($database)->select($sql);

        if (!$domain_scene) {
            $values = array(
                $scene->id,
                $scene->preview,
                $scene->thumbs,
                $scene->iframe,
                $scene->status,
                $scene->duration,
                $scene->rate,
                $scene->channel_id,
                date("Y-m-d H:i:s"),
                date("Y-m-d H:i:s"),
            );

            $sql_insert = 'insert into scenes (id, preview, thumbs, iframe, status, duration, rate, channel_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            DB::connection($database)->insert($sql_insert, $values);

            $this->syncSceneTags($database, $scene, $domain_scene);
            foreach ($languages as $lang) {
                $translation = $scene->translations()->where('language_id', $lang->id)->first();

                $values = array(
                    $translation->id,
                    $scene->id,
                    $lang->id,
                    ($translation->title != "") ? $translation->title : null,
                    ($translation->permalink != "") ? $translation->permalink : null,
                    ($translation->description != "") ? $translation->description : null,
                );

                DB::connection($database)->insert('insert into scene_translations (id, scene_id, language_id, title, permalink, description) values (?, ?, ?, ?, ?, ?)', $values);
            }
        } else {
            $sql_update = "UPDATE scenes SET status=".$scene->status . ",
                               preview = '".$scene->preview."',
                               thumbs = '".$scene->thumbs."',
                               iframe = '".$scene->iframe."',
                               status = ".$scene->status.",
                               rate = ".$scene->rate."
                                WHERE id=".$scene->id;

            DB::connection($database)->update($sql_update);

            $this->syncSceneTags($database, $scene, $domain_scene);

            foreach ($languages as $lang) {
                $translation = $scene->translations()->where('language_id', $lang->id)->first();

                $sql_update = "UPDATE scene_translations SET
                            scene_id=" . $scene->id . ",
                            language_id=" . $lang->id. ",
                            title=" . DB::connection()->getPdo()->quote((($translation->title != "") ? $translation->title : "")). ",
                            permalink=" . DB::connection()->getPdo()->quote((($translation->permalink != "") ? $translation->permalink : "")) . ",
                            description=" . DB::connection()->getPdo()->quote((($translation->description != "") ? $translation->description : "")) . "
                            where id=" . $translation->id;
                DB::connection($database)->update($sql_update);
            }
        }

        return redirect()->route('content', [
            'locale' => $this->locale,
            'q'      => Request::get("q"),
            'tag_q'  => Request::get("tag_q"),
            'page'   => Request::get("page")
        ]);
    }

    public function syncSceneTags($database, $scene, $domainScene)
    {
        $tagsScene = $scene->tags()->get();

        DB::connection($database)->table('scene_tag')->where('scene_id', $scene->id)->delete();

        foreach ($tagsScene as $tag) {
            $scene_tag = SceneTag::where('scene_id', $scene->id)->where('tag_id', $tag->id)->first();
            $sql_insert = "insert into scene_tag (id, tag_id, scene_id) values ($scene_tag->id, $tag->id, $scene->id)";
            DB::connection($database)->insert($sql_insert);
        }
    }

    public function saveTranslation($locale, $scene_id)
    {
        $title = Request::input('title');
        $description = Request::input('description');

        $sceneTranslation = SceneTranslation::where('scene_id', $scene_id)
            ->where('language_id', $this->language->id)
            ->first();

        if ($sceneTranslation) {

            $sceneTranslation->title = $title;
            $sceneTranslation->permalink = str_slug($title);
            $sceneTranslation->description = $description;
            $sceneTranslation->save();

            return json_encode(array(
                'description' => $sceneTranslation->description,
                'scene_id'    => $scene_id,
                'status'      => 1
            ));
        } else {
            return json_encode(array('status'=>0));
        }
    }

    public function stats()
    {
        $scenes = Scene::getAllTranslated($this->language->id)->get();

        $amountTitle = "";
        foreach ($scenes as $scene) {
            $amountTitle.=$scene->title;
        }

        $words = array_count_values(explode(" ", $amountTitle));
        arsort($words);

        return view('stats', [
            'language'     => $this->language,
            'languages'    => $this->languages,
            'locale'       => $this->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->sites,
            'words'        => $words
        ]);
    }
}