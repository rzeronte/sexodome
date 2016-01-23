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

    public function __construct()
    {
        $locale = Route::current()->getParameter('locale');

        // set locale
        $this->locale = $locale;

        // current language
        $this->language = Language::where('code', 'like', $locale)->first();

        // all valid languages
        $this->languages = Language::where('status', 1)->get();

        // results per page
        $this->perPageTags = 150;
        $this->perPageScenes = 20;
    }

    public function index($locale)
    {
        $query_string = Request::get('q');

        // scenes
        $scenes = Scene::getTranslationSearch($query_string, $this->language->id);

        return view('index', [
            'scenes'       => $scenes->orderBy('scene_id', 'desc')->paginate($this->perPageScenes),
            'query_string' => $query_string,
            'language'     => $this->language,
            'languages'    => $this->languages,
            'locale'       => $this->locale,
            'title'        => "Admin Panel",
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
                    $translation->title,
                    $translation->permalink,
                    $translation->description,
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
                            title=" . DB::connection()->getPdo()->quote($translation->title). ",
                            permalink=" . DB::connection()->getPdo()->quote($translation->permalink) . ",
                            description='" . $translation->description. "' where id=" . $translation->id;

                DB::connection($database)->update($sql_update);
            }
        }

        return redirect()->route('content', ['locale' => $this->locale]);
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

}