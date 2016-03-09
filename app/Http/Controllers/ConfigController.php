<?php

namespace App\Http\Controllers;

use App;
use DB;
use Request;
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;
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
use App\Model\SceneTagTier;
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

        App::setLocale($locale);

        // set locale
        $this->locale = $locale;

        // current language
        $this->language = Language::where('code', 'like', $locale)->first();
        // all valid languages
        $this->languages = Language::where('status', 1)->get();

        // results per page
        $this->perPageTags = 50;
        $this->perPageScenes = 10;

        //sites
        $this->sites = Site::all();
    }

    public function home()
    {
        return redirect()->route('content', ['locale' => "es"]);
    }

    public function index($locale)
    {
        $query_string = Request::get('q');
        $tag_query_string = Request::get('tag_q');
        $publish_for = Request::get('publish_for');  //site or 'notpublished'
        $duration = Request::get('duration');
        $scene_id = Request::get('scene_id');

        $remote_scenes = false;
        if ($publish_for && $publish_for !== 'notpublished') {
            $remote_scenes = Scene::getRemoteSceneIdsFor($publish_for);
        }

        $scenes = Scene::getScenesForExporterSearch(
            $query_string,
            $tag_query_string,
            $remote_scenes,
            $this->language->id,
            $duration,
            $publish_for,
            $scene_id
        );

        return view('index', [
            'scenes'       => $scenes->orderBy('scenes.id', 'desc')->paginate($this->perPageScenes),
            'total_scenes' => $scenes->count(),
            'query_string' => $query_string,
            'tag_q'        => $tag_query_string,
            'publish_for'  => $publish_for,
            'language'     => $this->language,
            'languages'    => $this->languages,
            'locale'       => $this->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->sites,
            'duration'     => $duration
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
                (!strlen($scene->thumb_index)?"NULL": $scene->thumb_index),
                $scene->iframe,
                1,
                $scene->duration,
                $scene->rate,
                $scene->channel_id,
                date("Y-m-d H:i:s"),
                date("Y-m-d H:i:s"),
                date("Y-m-d H:i:s"),
            );

            $sql_insert = 'insert into scenes (id, preview, thumbs, thumb_index, iframe, status, duration, rate, channel_id, created_at, updated_at, published_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            DB::connection($database)->insert($sql_insert, $values);

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
            $this->syncSceneTags($database, $scene, $domain_scene);

        } else {
            $sql_update = "UPDATE scenes SET status=".$scene->status . ",
                               preview = '".$scene->preview."',
                               thumbs = '".$scene->thumbs."',
                               thumb_index = ".(!strlen($scene->thumb_index)?"NULL": $scene->thumb_index).",
                               iframe = '".$scene->iframe."',
                               status = ".$scene->status.",
                               rate = ".$scene->rate.",
                               updated_at = '".date('Y-m-d H:i:s')."',
                               published_at = '".date('Y-m-d H:i:s')."'
                                WHERE id=".$scene->id;

            DB::connection($database)->update($sql_update);

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
            $this->syncSceneTags($database, $scene, $domain_scene);
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
        $selectedThumb = Request::input('selectedThumb', null);
        $tags_string = Request::input('tags');
        $tags_string = explode(",", $tags_string);

        $sceneTranslation = SceneTranslation::where('scene_id', $scene_id)
            ->where('language_id', $this->language->id)
            ->first();

        $scene = Scene::find($scene_id);
        $scene->thumb_index = $selectedThumb;
        $scene->save();

        if ($sceneTranslation) {

            $sceneTranslation->title = $title;
            $sceneTranslation->permalink = str_slug($title);
            $sceneTranslation->description = $description;
            $sceneTranslation->save();

            // tags
            DB::connection('mysql')->table('scene_tag')->where('scene_id', $scene_id)->delete();
            foreach($tags_string as $tag_string) {
                // Si no tiene el tag, lo asociamos
                $tag = Tag::getTranslationByName($tag_string, $this->language->id)->first();
                if (!Scene::hasTag($scene_id, $tag->id)) {
                    $tagScene = new SceneTag();
                    $tagScene->scene_id = $scene_id;
                    $tagScene->tag_id = $tag->id;
                    $tagScene->save();
                }
            }
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

    public function ajaxTags($locale)
    {
        $term = Request::get('term');
        $tags = Tag::getTranslationSearch($term, $this->language->id)->get();

        $select_tags = [];
        foreach($tags as $tag) {
            $select_tags[] = $tag->name;
        }

        return json_encode($select_tags);
    }

    public function scenePreview($locale, $scene_id)
    {

        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort("404", "Scene not found");
        }

        return view('_ajax_preview', [
            'language'     => $this->language,
            'languages'    => $this->languages,
            'locale'       => $this->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->sites,
            'scene'        => $scene
        ]);

    }

    public function sites()
    {
        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff." -7 days"));

        if (Request::isMethod('post')) {

            //sites
            foreach($this->sites as $site) {
                DB::connection('mysql')
                    ->table('site_tagtiers')
                    ->where('site_id', $site->id)
                    ->delete()
                ;

                $site->ga_account = Request::input('ga_view_'.$site->id);
                $site->save();

                //tiers
                for($i=1 ; $i<= Site::getNumTiers(); $i++) {

                    $tags_string = Request::input('tier'.$i.'_'.$site->id);

                    if (strlen($tags_string)) {
                        $tags_string = explode(",", $tags_string);

                        foreach($tags_string as $tag_string) {
                            // Si no tiene el tag, lo asociamos
                            $tag = Tag::getTranslationByName($tag_string, $this->language->id)->first();

                            if ($tag) {
                                if (!Site::hasTag($site->id, $tag->id, "tier".$i)) {
                                    $tagSite = new App\Model\SiteTagTier();
                                    $tagSite->site_id = $site->id;
                                    $tagSite->tag_id = $tag->id;
                                    $tagSite->tipo= "tier".$i;

                                    $tagSite->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('sites', [
            'language'     => $this->language,
            'languages'    => $this->languages,
            'locale'       => $this->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->sites,
            'fi'           => $fi,
            'ff'           => $ff,
        ]);
    }

    public function tagTiersInfo($locale)
    {
        $site_name = Request::input("site"); // sitename == database name

        $site = Site::where('name', $site_name)->first();

        if (!$site) {
            abort(404, 'Site not found');
        }

        $tier1 = $site->tags()->where('tipo', 'tier1')->get();
        $tier2 = $site->tags()->where('tipo', 'tier2')->get();
        $tier3 = $site->tags()->where('tipo', 'tier3')->get();

        return view('_ajax_tagtiers', [
            'tier1'     => $tier1,
            'tier2'     => $tier2,
            'tier3'     => $tier3,
            'language'  => $this->language,
            'database'  => $site_name
        ]);
    }

    public function scenePublicationInfo($locale, $sceneId)
    {
        $scene = Scene::find($sceneId);

        if (!$scene) {
            abort(404, 'Scene not found');
        }

        return view('_ajax_publication_info', [
            'scene'     => $scene,
            'language'  => $this->language,
            'languages' => $this->languages
        ]);
    }

    public function siteKeywords($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $keywords = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getTopKeyWords(90, $maxResults = 30);

        return view('_ajax_site_keywords', [
            'keywords' => $keywords,
            'language'  => $this->language,
            'languages' => $this->languages
        ]);
    }

    public function siteReferrers($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $referrers= LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getTopReferrers(90, $maxResults = 30);

        return view('_ajax_site_referrers', [
            'referrers' => $referrers,
            'language'  => $this->language,
            'languages' => $this->languages
        ]);

    }

    public function sitePageViews($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $pageViews = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getMostVisitedPages(90, $maxResults = 30);

        return view('_ajax_site_pageviews', [
            'pageViews' => $pageViews,
            'language'  => $this->language,
            'languages' => $this->languages
        ]);
    }

    public function sceneThumbs($locale, $scene_id)
    {
        $scene = Scene::find($scene_id);
        if (!$scene) {
            abort(404, "Not found");
        }
        return view('_ajax_scene_thumbs', [
            'scene'     => $scene,
            'language'  => $this->language,
            'languages' => $this->languages
        ]);
    }
}