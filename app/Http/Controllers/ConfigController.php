<?php

namespace App\Http\Controllers;

use App;
use DB;
use function GuzzleHttp\json_encode;
use GuzzleHttp\Psr7\Response;
use okw\CF\Exception\CFException;
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
use App\Model\Category;
use App\Model\CategoryTranslation;
use App\Model\SceneTag;
use App\Model\Tag;
use App\Model\TagTranslation;
use App\Model\Origin;
use App\Model\SceneTagTier;
use App\Model\LanguageTag;
use App\Model\Tweet;
use App\Model\Zone;
use App\Model\Ad;
use App\Model\Site;
use App\Model\Channel;
use App\Model\InfoJobs;
use App\Model\SiteCategory;
use App\Jobs\importScenesFromFeed;
use App\Model\Pornstar;
use App\Model\Popunder;
use Illuminate\Support\Facades\Log;
use okw\CF\CF;
use Auth;
use App\rZeBot\rZeBotCommons;
use Illuminate\Support\Facades\Artisan;

class ConfigController extends Controller
{
    var $commons;

    public function __construct()
    {
        $this->commons = new rZeBotCommons();

        $this->middleware('CheckVerifyUser');
        $this->middleware('auth');
    }

    public function home()
    {
        return redirect()->route('sites', ['locale' => $this->commons->locale]);
    }

    public function unverified()
    {
        Auth::logout();
        return view('panel.unverified');
    }

    public function welcome()
    {
        return view('panel.welcome');
    }

    public function scenes()
    {
        $query_string = Request::get('q');
        $tag_query_string = Request::get('tag_q');
        $publish_for = Request::get('publish_for');  //site_id or 'notpublished'
        $duration = Request::get('duration');
        $scene_id = Request::get('scene_id');
        $category_string= Request::get('category_string');
        $empty_title = (Request::get('empty_title') == "on")?true:false;
        $empty_description = (Request::get('empty_description') == "on")?true:false;

        $scenes = Scene::getScenesForExporterSearch(
            $query_string,
            $tag_query_string,
            $this->commons->language->id,
            $duration,
            $publish_for,
            $scene_id,
            $category_string,
            $empty_title,
            $empty_description,
            Auth::user()->id
        );

        $sites = Site::where('user_id', '=', Auth::user()->id)->get();

        return view('panel.scenes', [
            'scenes'       => $scenes->orderBy('scenes.id', 'desc')->paginate($this->commons->perPageScenes),
            'query_string' => $query_string,
            'tag_q'        => $tag_query_string,
            'publish_for'  => $publish_for,
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
            'title'        => "Admin Panel",
            'sites'        => $sites,
            'duration'     => $duration,
        ]);
    }

    public function ajaxSiteTags($locale, $site_id)
    {
        $query_string = Request::get('q');

        $site = Site::find($site_id);
        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $tags = Tag::getTranslationSearch($query_string, $this->commons->language->id)->where('site_id', $site_id)
            ->paginate($this->commons->perPageScenes);


        return view('panel.ajax._ajax_site_tags', [
            'site'       => $site,
            'tags'     => $tags,
            'locale'   => $this->commons->locale,
            'language' => $this->commons->language
        ]);
    }

    public function ajaxSiteCategories($locale, $site_id)
    {
        $query_string = Request::get('q');

        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $categories = Category::getTranslationSearch($query_string, $this->commons->language->id, $site->id)
            ->paginate($this->commons->perPageScenes);

        return view('panel.ajax._ajax_site_categories', [
            'site'       => $site,
            'categories' => $categories,
            'locale'     => $this->commons->locale,
            'language'   => $this->commons->language
        ]);
    }

    public function ajaxSiteWorkers($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $infojobs = $site->infojobs()->paginate(10);

        return view('panel.ajax._ajax_site_workers', [
            'site'      => $site,
            'locale'    => $this->commons->locale,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'infojobs'  => $infojobs,
        ]);
    }

    public function saveTagTranslation($locale, $tag_id)
    {
        $name = Request::input('language_' . $this->commons->language->id);

        $tagTranslation = TagTranslation::where('tag_id', $tag_id)
            ->where('language_id', $this->commons->language->id)
            ->first();

        $tagTranslation->name = $name;
        $tagTranslation->permalink = str_slug($name);
        $tagTranslation->save();

        $tag = Tag::find($tag_id);
        $tag->status = Request::input('status');
        $tag->save();

        // response json if ajax request
        return json_encode(array('status'=>1));
    }

    public function saveCategoryTranslation($locale, $category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            abort(404, "Category not found");
        }

        if (!(Auth::user()->id == $category->site->user->id)) {
            abort(401, "Unauthorized");
        }

        $name = Request::input('language_' . $this->commons->language->id);
        $thumb = Request::input('thumbnail');

        // Buscamos si existe otra categoría en el idioma utilizado con el mismo nombre
        $alreadyCategoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
            ->where('categories.site_id', $category->site->id)
            ->where('language_id', $this->commons->language->id)
            ->where('name', 'like', $name)
            ->where('categories.status', 1)
            ->where('categories.id', '<>', $category_id)
            ->first();

        if ($alreadyCategoryTranslation) {
            return json_encode(array('status'=> 0));
        }

        $categoryTranslation =  CategoryTranslation::where('category_id', $category_id)
            ->where('language_id', $this->commons->language->id)
            ->first();

        $categoryTranslation->name = $name;
        $categoryTranslation->permalink = str_slug($name);
        $categoryTranslation->thumb_locked = 1;
        $categoryTranslation->thumb = $thumb;
        $categoryTranslation->save();

        $category->status = Request::input('status');
        $category->save();

        if(Request::ajax()) {
            return json_encode(array('status'=>1));
        }
    }

    public function changeLocale($locale)
    {
        App::setLocale($locale);

        return redirect()->route('content', ['locale' => $this->commons->locale]);
    }

    public function saveTranslation($locale, $scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort(404, "Scene not found");
        }

        if (!(Auth::user()->id == $scene->site->user->id)) {
            abort(401, "Unauthorized");
        }

        $title = Request::input('title');
        $description = Request::input('description');
        $selectedThumb = Request::input('selectedThumb', null);
        $tags_string = Request::input('tags');
        $tags_string = explode(",", $tags_string);
        $categories_string = Request::input('categories');
        $categories_string = explode(",", $categories_string);

        $sceneTranslation = SceneTranslation::where('scene_id', $scene_id)
            ->where('language_id', $this->commons->language->id)
            ->first();

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
                $tag = Tag::getTranslationByName($tag_string, $this->commons->language->id)->first();
                if (!Scene::hasTag($scene_id, $tag->id)) {
                    $tagScene = new SceneTag();
                    $tagScene->scene_id = $scene_id;
                    $tagScene->tag_id = $tag->id;
                    $tagScene->save();
                }
            }

            // categories
            DB::connection('mysql')->table('scene_category')->where('scene_id', $scene_id)->delete();
            foreach($categories_string as $category_string) {
                // Si no tiene la categoría, lo asociamos
                $category = Category::getTranslationByName($category_string, $this->commons->language->id)->first();
                if (!Scene::hasCategory($scene_id, $category->id)) {
                    $categoryScene = new App\Model\SceneCategory();
                    $categoryScene->scene_id = $scene_id;
                    $categoryScene->category_id = $category->id;
                    $categoryScene->save();
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

    public function ajaxSitePornstars($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $pornstars = \App\Model\Pornstar::where('site_id', '=', $site_id)->paginate($this->commons->perPagePanelPornstars);

        return view('panel.ajax._ajax_site_pornstars', [
            'site'      => $site,
            'pornstars' => $pornstars,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale
        ]);
    }

    public function scenePreview($locale, $scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort("404", "Scene not found");
        }

        if (!(Auth::user()->id == $scene->site->user->id)) {
            abort(401, "Unauthorized");
        }

        return view('panel.ajax._ajax_preview', [
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
            'title'        => "Admin Panel",
            'scene'        => $scene
        ]);

    }

    public function fetch($site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(401, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $channel = Channel::where('name', '=', Input::get('feed_name'))->first();

        if (!$channel) {
            abort(404, "Channel not found");
        }

        // categories y tags son 'false' en string, por requisito del comando (refact)
        $categories = Input::get('categories', false);
        if (strlen($categories) == 0) {
            $categories = 'false';
        }

        $tags = Input::get('tags', false);
        if (strlen($tags) == 0) {
            $tags = 'false';
        }
        $queueParams = [
            'feed_name'  => Input::get('feed_name'),
            'site_id'    => Input::get('site_id'),
            'max'        => Input::get('max'),
            'duration'   => Input::get('duration'),
            'tags'       => $tags,
            'categories' => $categories,
        ];

        if (Input::get('only_with_pornstars') == 1) {
            $queueParams['only_with_pornstars'] = 'true';
        } else {
            $queueParams['only_with_pornstars'] = 'false';
        }

        $newInfoJob = new InfoJobs();
        $newInfoJob->site_id = $site_id;
        $newInfoJob->feed_id = $channel->id;
        $newInfoJob->created_at = date("Y:m:d H:i:s");
        $newInfoJob->serialized = \GuzzleHttp\json_encode($queueParams);
        $newInfoJob->save();

        $queueParams['job'] = $newInfoJob->id;

        try {
            $job = (new importScenesFromFeed($queueParams));
            $this->dispatch($job);

            return json_encode(['status' => true]);

        } catch(\Exception $e) {
            Log::info('[ERROR Al lanzar importScenesFromFeed]');

            return json_encode(['status' => false]);
        }

        return json_encode(['status' => false]);
    }

    public function sites()
    {
        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff." -30 days"));

        $sites = Site::where('user_id', '=', Auth::user()->id)
            ->orderBy('language_id', 'asc')
            ->get()
        ;

        return view('panel.sites', [
            'channels'  => Channel::all(),
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale,
            'title'     => "Admin Panel",
            'sites'     => $sites,
            'fi'        => $fi,
            'ff'        => $ff,
        ]);
    }

    public function site($locale, $site_id)
    {

        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        // Activamos el idioma del sitio
        if ($this->commons->language->id != $site->language_id) {
            App::setLocale($site->language->code);
            return redirect()->route('site', ['locale' => $site->language->code, $site_id]);
        }

        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff." -50 days"));

        return view('panel.site', [
            'channels'  => Channel::all(),
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale,
            'title'     => "Admin Panel",
            'site'      => $site,
            'sites'     => Site::where('user_id', '=', Auth::user()->id)->get(),
            'fi'        => $fi,
            'ff'        => $ff,
        ]);
    }

    public function siteKeywords($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

//        if (!(Auth::user()->id == $site->user->id)) {
//            abort(401, "Unauthorized");
//        }

        $keywords = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getTopKeyWords(90, $maxResults = 30);

        return view('panel.ajax._ajax_site_keywords', [
            'keywords' => $keywords,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
        ]);
    }

    public function siteReferrers($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

//        if (!(Auth::user()->id == $site->user->id)) {
//            abort(401, "Unauthorized");
//        }

        $referrers= LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getTopReferrers(90, $maxResults = 30);

        return view('panel.ajax._ajax_site_referrers', [
            'referrers' => $referrers,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
        ]);
    }

    public function sitePageViews($locale, $site_id)
    {
        $site = Site::find($site_id);

//        if (!$site) {
//            abort(404, "Site not found");
//        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $pageViews = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getMostVisitedPages(90, $maxResults = 30);

        return view('panel.ajax._ajax_site_pageviews', [
            'pageViews' => $pageViews,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
        ]);
    }

    public function sceneThumbs($locale, $scene_id)
    {
        $scene = Scene::find($scene_id);
        if (!$scene) {
            abort(404, "Not found");
        }

        return view('panel.ajax._ajax_scene_thumbs', [
            'scene'     => $scene,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
        ]);
    }

    public function ajaxCronJobs($locale, $site_id)
    {
        $site = Site::find($site_id);
        $scene = Scene::find($site_id);

        return view('panel.ajax._ajax_site_cronjobs', [
            'site'      => $site,
            'scene'     => $scene,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale
        ]);

    }

    public function updateSiteSEO($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        // Check site is from user
        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->status = Request::input('status');

        $site->language_id = Request::input('language_id');

        $site->category_url = Request::input('category_url');
        $site->pornstars_url = Request::input('pornstars_url');
        $site->pornstar_url = Request::input('pornstar_url');
        $site->video_url = Request::input('video_url');

        $site->contact_email = Request::input('contact_email');

        $site->title_index = Request::input('title_index');
        $site->title_category = Request::input('title_category');

        $site->description_index = Request::input('description_index');
        $site->description_category = Request::input('description_category');

        $site->title_pornstars = Request::input('title_pornstars');
        $site->title_pornstar = Request::input('title_pornstar');

        $site->description_pornstars = Request::input('description_pornstars');
        $site->description_pornstar = Request::input('description_pornstar');

        $site->domain = Request::input('domain');
        $site->head_billboard = Request::input('head_billboard');
        $site->link_billboard = Request::input('link_billboard');
        $site->google_analytics = Request::input('google_analytics');

        $site->banner_script1 = Request::input('banner_script1');
        $site->banner_script2 = Request::input('banner_script2');
        $site->banner_script3 = Request::input('banner_script3');

        $site->banner_mobile1 = Request::input('banner_mobile1');

        $site->banner_video1 = Request::input('banner_video1');
        $site->banner_video2 = Request::input('banner_video2');

        $site->button1_url = Request::input('button1_url');
        $site->button2_url = Request::input('button2_url');
        $site->button1_text = Request::input('button1_text');
        $site->button2_text = Request::input('button2_text');

        $site->save();

        return json_encode(array('status' => true));
    }

    public function removeCategory($locale, $category_id, $site_id)
    {
        $siteCategory = SiteCategory::where('category_id', $category_id)->where('site_id', $site_id)->first();
        $siteCategory->delete();

        return redirect()->route('site', ['locale' => $this->locale, $site_id]);
    }

    public function addCategory($locale, $category_id, $site_id)
    {
        $category = Category::find($category_id);

        // check if already exists
        if (Site::hasCategory($category->id, $site_id)) {
            Request::session()->flash('error', 'Category already exists!');
        } else {
            Request::session()->flash('success', 'Category added!');
            $newSiteCategory = new SiteCategory();
            $newSiteCategory->site_id = $site_id;
            $newSiteCategory->category_id = $category->id;
            $newSiteCategory->save();
        }

        return redirect()->route('site', [
            'locale'  => $this->commons->locale,
            'site_id' => $site_id,
            'page'    => Request::input('page')
        ]);
    }

    public function addSite($locale)
    {
        $sites = Site::where('user_id', '=', Auth::user()->id)->get();

        if (Request::isMethod('post')) {

            // check if already exists
            if (Input::get('type_site') == 1) {
                $site = Site::where('domain', '=', trim(Input::get('domain')))->first();
            } else {
                $site = Site::where('name', '=', trim(Input::get('subdomain')))->first();
            }

            // if exists return with custom error
            if ( $site ) {
                if (Input::get('type_site') == 1) {
                    Request::session()->flash('error_domain', 'Domain <'.trim(Input::get('domain')).'> already exists!');
                } else {
                    Request::session()->flash('error_subdomain', 'Subdomain <'.trim(Input::get('subdomain')).'> already exists!');
                }

                return view('panel.add_site', [
                    'language'  => $this->commons->language,
                    'languages' => $this->commons->languages,
                    'locale'    => $this->commons->locale,
                    'sites'     => $sites
                ]);
            }

            // create new site for current user
            $newSite              = new Site();
            $newSite->user_id     = Auth::user()->id;
            $newSite->name        = Input::get('subdomain');
            $newSite->language_id = env("DEFAULT_FETCH_LANGUAGE", 2);
            $newSite->domain      = Input::get('domain');
            $newSite->have_domain = Input::get('type_site');

            $newSite->save();

            if ($newSite->have_domain == 0) {
                // Alta del subdominio en CF
                $clientCF = new CF($this->commons->cloudFlareCfg['email'],$this->commons->cloudFlareCfg['key']);
                try {
                    $subdomain = Input::get('subdomain');

                    $clientCF->rec_new(array(
                        'z'       => $this->commons->cloudFlareCfg['zone'],
                        'name'    => $subdomain.".".$this->commons->cloudFlareCfg['zone'],
                        'ttl'     => 1,
                        'type'    => 'A',
                        'content' => $this->commons->cloudFlareCfg['ip']
                    ));

                    return redirect()->route('sites', [
                        'locale'  => $this->commons->locale
                    ]);

                } catch(CFException $e) {
                    Request::session()->flash('error_subdomain', '(DNS) subdomain <'.Input::get('subdomain').'> already exists!');
                }
            } else {
                return redirect()->route('sites', [
                    'locale'  => $this->commons->locale
                ]);
            }
        }

        return view('panel.add_site', [
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale,
            'sites'     => $sites
        ]);
    }

    public function deleteSite($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->delete();

        return redirect()->route('sites', ['locale' => $this->commons->locale]);
    }

    public function checkSubdomain($locale)
    {
        $subdomain = Input::get('subdomain');

        if (strlen($subdomain) == 0) {
            abort(404,'Not allowed');
        }

        $sites = Site::where('name', '=', $subdomain)->count();

        if ($sites == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        return json_encode(array('status' => $status));
    }

    public function checkDomain($locale)
    {
        $domain = Input::get('domain');

        if (strlen($domain) == 0) {
            abort(404,'Not allowed');
        }

        $sites = Site::where('domain', '=', $domain)->count();

        if ($sites == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        return json_encode(array('status' => $status));
    }

    public function updateGoogleData($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $site->ga_account = Request::input('ga_view_'.$site->id);

        try {
            $site->save();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return json_encode(array('status' => $status));
    }

    public function updateIframeData($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $site->iframe_site_id = (Request::input('iframe_site_id_'.$site->id) != "") ? Request::input('iframe_site_id_'.$site->id) : null;

        try {
            $site->save();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return json_encode(array('status' => $status));
    }

    public function updateLogo($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $delete_header = Input::get('header_delete');

        // logo validator
        $v = Validator::make(Request::all(), [
            'logo' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // favicon validator
        $vF = Validator::make(Request::all(), [
            'favicon' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // favicon validator
        $vH = Validator::make(Request::all(), [
            'header' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        $v->after(function($validator) {
            $extensions_acepted = array("png");
            $extension = Input::file('logo')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Logo invalid!');
            }
        });

        $vF->after(function($validator) {
            $extensions_acepted = array("png");
            $extension = Input::file('favicon')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Logo invalid!');
            }
        });

        $vH->after(function($validator) {
            $extensions_acepted = array("png");
            $extension = Input::file('header')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Header invalid!');
            }
        });

        if (Request::hasFile('logo') && !$v->fails()) {
            Request::session()->flash('success', 'Logo uploaded successful');
            Request::file('logo')->move(rZeBotCommons::getLogosFolder(), md5($site_id).".".Request::file('logo')->getClientOriginalExtension());
        } else {
            Request::session()->flash('error', 'Upload invalid file. Check your Logo file, size ane extension (pngs only)!');
        }

        if (Request::hasFile('favicon') && !$vF->fails()) {
            Request::session()->flash('success', 'Logo uploaded successful');
            Request::file('favicon')->move(rZeBotCommons::getFaviconsFolder(), md5($site_id).".".Request::file('favicon')->getClientOriginalExtension());
        } else {
            Request::session()->flash('error', 'Upload invalid file. Check your Favicon file, size ane extension (pngs only)!');
        }

        if (Request::hasFile('header') && !$vH->fails() && $delete_header != 1) {
            Request::session()->flash('success', 'Header uploaded successful');
            Request::file('header')->move(rZeBotCommons::getHeadersFolder(), md5($site_id).".".Request::file('header')->getClientOriginalExtension());
        } else {
            if ($delete_header == 1) {
                unlink(rZeBotCommons::getHeadersFolder() . md5($site_id).".png");
            } else {
                Request::session()->flash('error', 'Upload invalid file. Check your Header file, size ane extension (png only)!');
            }
        }

        return redirect()->route('site', ['locale' => $this->commons->locale, 'site_id' => $site->id]);
    }

    public function updateColors($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->color = (Request::input('color') != "") ? Request::input('color') : null;
        $site->color2 = (Request::input('color2') != "") ? Request::input('color2') : null;
        $site->color3 = (Request::input('color3') != "") ? Request::input('color3') : null;
        $site->color4 = (Request::input('color4') != "") ? Request::input('color4') : null;
        $site->color5 = (Request::input('color5') != "") ? Request::input('color5') : null;
        $site->color6 = (Request::input('color6') != "") ? Request::input('color6') : null;
        $site->color7 = (Request::input('color7') != "") ? Request::input('color7') : null;
        $site->color8 = (Request::input('color8') != "") ? Request::input('color8') : null;
        $site->color9 = (Request::input('color9') != "") ? Request::input('color9') : null;
        $site->color10 = (Request::input('color10') != "") ? Request::input('color10') : null;
        $site->color11 = (Request::input('color11') != "") ? Request::input('color11') : null;
        $site->color12 = (Request::input('color12') != "") ? Request::input('color12') : null;

        $site->save();
        $status = true;

        Artisan::call('zbot:css:update', [
            '--site_id' => $site->id
        ]);

        return json_encode(array('status' => $status));
    }

    public function ajaxSaveCronJob($locale)
    {
        $channel = Channel::where('name', Input::get('feed_name'))->first();

        if (!$channel) {
            abort(404, "Channel not found");
        }

        $categories = Input::get('categories', false);
        $tags = Input::get('tags', false);

        if (strlen($categories) == 0) {
            $categories = 'false';
        }

        if (strlen($tags) == 0) {
            $tags = 'false';
        }

        $queueParams = [
            'feed_name'  => Input::get('feed_name'),
            'site_id'    => Input::get('site_id'),
            'max'        => Input::get('max'),
            'duration'   => Input::get('duration'),
            'categories' => $categories,
            'tags'       => $tags,
        ];

        if (Input::get('only_with_pornstars') == 1) {
            $queueParams['only_with_pornstars'] = 'true';
        } else {
            $queueParams['only_with_pornstars'] = 'false';
        }

        $cronjob = new App\Model\CronJob();
        $cronjob->site_id = Input::get('site_id');
        $cronjob->channel_id = $channel->id;
        $cronjob->params = \GuzzleHttp\json_encode($queueParams);
        $cronjob->save();

        $status = true;

        return json_encode(array('status' => $status));
    }

    public function deleteCronJob($locale, $cronjob_id)
    {
        $cronjob = App\Model\CronJob::find($cronjob_id);

        if (!$cronjob) {
            abort(404, "Cronjob not found");
        }

        if (!(Auth::user()->id == $cronjob->site->user->id)) {
            abort(401, "Unauthorized");
        }

        $cronjob->delete();

        $status = true;

        return json_encode(array('status' => $status));
    }

    public function ajaxPopunders($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $popunders = $site->popunders()->get();

        return view('panel.ajax._ajax_site_popunders', [
            'popunders' => $popunders,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale,
        ]);
    }

    public function ajaxSavePopunder($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $url = Input::get('url', false);

        $newPopunder = new App\Model\Popunder();
        $newPopunder->url = $url;
        $newPopunder->site_id = $site->id;
        $newPopunder->save();

        return json_encode(array('status' => $status = true));
    }

    public function ajaxDeletePopunder($locale, $popunder_id)
    {
        $popunder = Popunder::find($popunder_id);

        if (!$popunder) {
            abort(404, "Popunder not found");
        }

//        if (!(Auth::user()->id == $popunder->site->user->id)) {
//            abort(401, "Unauthorized");
//        }

        $popunder->delete();

        return json_encode(array('status' => $status = true));
    }

    public function categoryThumbs($locale, $category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            abort(404, "Category not found");
        }

        $site = $category->site;

        $site = Site::find($site->id);

        $category_scenes = $category->scenes()->orderByRaw("RAND()")->limit(100)->get();
        $site_scenes =  $site->scenes()->orderByRaw("RAND()")->limit(50)->get();

        return view('panel.ajax._ajax_category_thumbs', [
            'category'        => $category,
            'category_scenes' => $category_scenes,
            'site_scenes'     => $site_scenes,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages
        ]);
    }

    public function categoryUnlock($locale, $category_translation_id)
    {
        $categoryTranslation = CategoryTranslation::find($category_translation_id);

        if (!$categoryTranslation) {
            $status = false;
        } else {
            $status = true;
            $categoryTranslation->thumb_locked = NULL;
            $categoryTranslation->save();
        }

        return json_encode(array('status' => $status));
    }

    public function fixTranslations($locale)
    {
        return view('panel.fixtranslations', [
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale,
            'sites'     => $sites = Site::where('user_id', '=', Auth::user()->id)->get(),
            'fixs'      => App\Model\FixTranslation::where('user_id', Auth::user()->id)->get()
        ]);
    }

    public function deleteFixTranslation($locale, $fixtranslation_id)
    {
        $fixtranslation = App\Model\FixTranslation::find($fixtranslation_id);

        if (!$fixtranslation) {
            abort(404, "FixTranslation not found");
        }

        if (!(Auth::user()->id == $fixtranslation->user->id)) {
            abort(401, "Unauthorized");
        }

        $fixtranslation->delete();

        return redirect()->route('fixtranslations', ['locale' => $this->commons->locale]);
    }

    public function AddFixTranslation($locale)
    {
        $newFixTranslation = new App\Model\FixTranslation();
        $newFixTranslation->user_id = Auth::user()->id;
        $newFixTranslation->language_id = Request::input('language');
        $newFixTranslation->from = Request::input('from');
        $newFixTranslation->to = Request::input('to');

        $newFixTranslation->save();

        return redirect()->route('fixtranslations', ['locale' => $this->commons->locale]);
    }

    public function uploadCategory($category_id)
    {
        // logo validator
        $v = Validator::make(Request::all(), [
            'file' => 'required|mimes:jpg,jpeg',      // max=50*1024; min=3*1024
        ]);

        if ($v->fails()) {
            $data = ["error" => "Upload invalid file. Check your file, size ane extension (JPG only)!"];

            return \GuzzleHttp\json_encode($data);
        }

        $fileName = md5(microtime().$category_id).".jpg";

        $final_url = "http://" . env('MAIN_PLATAFORMA_DOMAIN', 'sexodome.com') ."/thumbnails_categories/".$fileName;

        $destinationPath = "thumbnails/";

        $fileNameFinal = md5($final_url).".jpg";

        Request::file('file')->move($destinationPath, $fileNameFinal);

        $data = ["files" => [
            [
                "category_id" => $category_id,
                "name"        => $fileName,
                "url"         => $final_url,
                "md5_url"     => "http://" . env('MAIN_PLATAFORMA_DOMAIN', 'sexodome.com') ."/thumbnails/".md5($final_url).".jpg",
            ]
        ]];

        return \GuzzleHttp\json_encode($data);
    }

    public function orderCategories($locale, $site_id)
    {
        $sites = Site::where('user_id', '=', Auth::user()->id)
            ->orderBy('language_id', 'asc')
            ->get()
        ;

        if (Request::input('o') != "") {

            foreach(Request::input('o') as $category) {
                $categoyBBDD = Category::find($category['i']);
                $categoyBBDD->cache_order = -1 * $category['o'];
                $categoyBBDD->save();
            }

            return json_encode(['status'=>true]);
        }

        $site = Site::find($site_id);

        DB::table('categories')->where('site_id', $site->id)->update(['cache_order' => -999999]);

        $categories = Category::getTranslationByStatus(1, $this->commons->language->id)
            ->where('site_id', '=', $site->id)
            ->orderBy('categories.cache_order', 'DESC')
            ->orderBy('categories.nscenes', 'DESC')
            ->limit(40)
            ->get()
        ;

        return view('panel.categories_order', [
            'sites'      => $sites,
            'site'       => Site::find($site_id),
            'language'   => $this->commons->language,
            'languages'  => $this->commons->languages,
            'locale'     => $this->commons->locale,
            'categories' => $categories
        ]);
    }
}
