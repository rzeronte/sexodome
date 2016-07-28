<?php

namespace App\Http\Controllers;

use App;
use DB;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use okw\CF\Exception\CFException;
use Request;
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;
use Symfony\Component\Debug\ExceptionHandler;
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
use App\Model\Language;
use App\Model\SceneTagTier;
use App\Model\LanguageTag;
use App\Model\Tweet;
use App\Model\Zone;
use App\Model\Ad;
use App\Model\Site;
use App\Model\Channel;
use App\Model\Logpublish;
use App\Model\InfoJobs;
use Illuminate\Support\Facades\Artisan;
use App\Model\SiteCategory;
use App\Jobs\importScenesFromFeed;
use Illuminate\Support\Facades\Log;
use okw\CF\CF;
use Auth;
use App\rZeBot\rZeBotCommons;

class ConfigController extends Controller
{
    var $commons;

    public function __construct()
    {
        $this->commons = new rZeBotCommons();

        $this->middleware('auth');
    }

    public function home()
    {
        return redirect()->route('sites', ['locale' => $this->commons->locale]);
    }

    public function index()
    {
        $query_string = Request::get('q');
        $tag_query_string = Request::get('tag_q');
        $publish_for = Request::get('publish_for');  //site_id or 'notpublished'
        $duration = Request::get('duration');
        $scene_id = Request::get('scene_id');
        $category_id = Request::get('category_id');
        $empty_title = (Request::get('empty_title') == "on")?true:false;
        $empty_description = (Request::get('empty_description') == "on")?true:false;

        $scenes = Scene::getScenesForExporterSearch(
            $query_string,
            $tag_query_string,
            $this->commons->language->id,
            $duration,
            $publish_for,
            $scene_id,
            $category_id,
            $empty_title,
            $empty_description,
            Auth::user()->id
        );

        return view('panel.index', [
            'scenes'       => $scenes->orderBy('scenes.id', 'desc')->paginate($this->commons->perPageScenes),
            'query_string' => $query_string,
            'tag_q'        => $tag_query_string,
            'publish_for'  => $publish_for,
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->commons->sites,
            'duration'     => $duration,
            'categories'   => Category::all()
        ]);
    }

    public function tags($locale, $site_id)
    {
        $query_string = Request::get('q');

        $site = Site::find($site_id);
        if (!$site) {
            abort(404, "Site not found");
        }

        $tags = Tag::getTranslationSearch($query_string, $this->commons->language->id);


        return view('panel.tags', [
            'site'         => $site,
            'tags'         => $tags->paginate($this->commons->perPageScenes),
            'query_string' => $query_string,
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
        ]);
    }

    public function categories($locale, $site_id)
    {
        $query_string = Request::get('q');

        $site = Site::find($site_id);
        if (!$site) {
            abort(404, "Site not found");
        }

        $categories = Category::getTranslationSearch($query_string, $this->commons->language->id);

        return view('panel.categories', [
            'site'         => $site,
            'categories'   => $categories->paginate($this->commons->perPageScenes),
            'query_string' => $query_string,
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
        ]);
    }

    public function addTag($locale, $permalinkTag)
    {
        $tag = TagTranslation::where('permalink', $permalinkTag)->first()->tag;

        // check if already exists
        if (Language::hasTag($tag->id, $this->language->id)) {
            Request::session()->flash('error', 'Tag already exists!');
        } else {
            Request::session()->flash('success', 'Tag added!');
            $newLanguageTag = new App\Model\LanguageTag();
            $newLanguageTag->language_id = $this->language->id;
            $newLanguageTag->tag_id = $tag->id;
            $newLanguageTag->save();
        }

        return redirect()->route('tags', [
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale,
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
        $name = Request::input('language_' . $this->commons->language->id);

        $categoryTranslation =  CategoryTranslation::where('category_id', $category_id)
            ->where('language_id', $this->commons->language->id)
            ->first();

        $categoryTranslation->name = $name;
        $categoryTranslation->permalink = str_slug($name);
        $categoryTranslation->save();

        $category = App\Model\Category::find($category_id);
        $category->status = Request::input('status');
        $category->save();

        // response json if ajax request
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

    public function ajaxTags($locale)
    {
        $term = Request::get('term');
        $tags = Tag::getTranslationSearch($term, $this->commons->language->id)->get();

        $select_tags = [];
        foreach($tags as $tag) {
            $select_tags[] = $tag->name;
        }

        return json_encode($select_tags);
    }

    public function ajaxCategories($locale)
    {
        $term = Request::get('term');
        $categories = Category::getTranslationSearch($term, $this->commons->language->id)->get();

        $select_categories = [];
        foreach($categories as $category) {
            $select_categories[] = $category->name;
        }

        return json_encode($select_categories);
    }

    public function ajaxCategoriesOptions()
    {
        $site_id = Request::get('site_id');

        $categories = Category::getTranslationSearch(
                false,
                $this->commons->language->id,
                $site_id
            )
            ->orderBy('name', 'asc')
            ->get()
        ;

        return view('panel._ajax_categories_options', [
            'categories'=> $categories,
        ]);
    }

    public function scenePreview($locale, $scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort("404", "Scene not found");
        }

        return view('panel._ajax_preview', [
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
            'title'        => "Admin Panel",
            'sites'        => $this->commons->sites,
            'scene'        => $scene
        ]);

    }

    public function fetch()
    {
        if (Request::isMethod('post')) {

            $channel = Channel::where('name', '=', Input::get('feed_name'))->first();

            if (!$channel) {
                abort(404, "Channel not found");
            }

            $newInfoJob = new InfoJobs();
            $newInfoJob->site_id = Input::get('site_id');
            $newInfoJob->feed_id = $channel->id;
            $newInfoJob->created_at = date("Y:m:d H:i:s");
            $newInfoJob->save();

            $categories = Input::get('categories');

            if (count($categories == 1) && !strlen($categories[0])) {
                $categories = 'false';
            } else {
                $categories = implode(",", $categories);
            }

            $queueParams = [
                'feed_name'  => Input::get('feed_name'),
                'site_id'    => Input::get('site_id'),
                'max'        => Input::get('max'),
                'duration'   => Input::get('duration'),
                'categories' => $categories,
                'job'        => $newInfoJob->id
            ];

            try {

                $job = (new importScenesFromFeed($queueParams));
                $this->dispatch($job);

                return json_encode(['status' => true]);

            } catch(\Exception $e) {
                Log::info('[ERROR Al lanzar importScenesFromFeed]');

                return json_encode(['status' => false]);
            }

        }

        return json_encode(['status' => false]);
    }

    public function sites()
    {
        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff." -7 days"));

        return view('panel.sites', [
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale'    => $this->commons->locale,
            'title'     => "Admin Panel",
            'sites'     => $this->commons->sites,
            'fi'        => $fi,
            'ff'        => $ff,
        ]);
    }

    public function scenePublicationInfo($locale, $sceneId)
    {
        $scene = Scene::find($sceneId);

        if (!$scene) {
            abort(404, 'Scene not found');
        }

        return view('panel._ajax_publication_info', [
            'scene'     => $scene,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
        ]);
    }

    public function siteKeywords($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $keywords = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getTopKeyWords(90, $maxResults = 30);

        return view('panel._ajax_site_keywords', [
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

        $referrers= LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getTopReferrers(90, $maxResults = 30);

        return view('panel._ajax_site_referrers', [
            'referrers' => $referrers,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
        ]);

    }

    public function sitePageViews($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $pageViews = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getMostVisitedPages(90, $maxResults = 30);

        return view('panel._ajax_site_pageviews', [
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

        return view('panel._ajax_scene_thumbs', [
            'scene'     => $scene,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
        ]);
    }

    public function spinScene($locale, $scene_id)
    {
        $scene = Scene::find($scene_id);
        if (!$scene) {
            abort(404, "Not found");
        }

        $exitCodeTitle = "not exist title";
        $exitCodeDescription = "not exist description";

        $translation = $scene->translations()->where('language_id', 1)->first();

        if ($translation->title != "") {
            $exitCodeTitle = Artisan::call('rZeBot:spinner:text', [
                'language' => 'es',
                'source'   => $translation->title,
                '--text'   => "true"
            ]);
        }

        if ($translation->description != "") {
            $exitCodeDescription = Artisan::call('rZeBot:spinner:text', [
                'language' => 'es',
                'source'   => $translation->description,
                '--text'   => "true"
            ]);
        }

        return view('panel._ajax_spin_scene', [
            'scene'               => $scene,
            'language'            => $this->commons->language,
            'languages'           => $this->commons->languages,
            'exitCodeTitle'       => $exitCodeTitle,
            'exitCodeDescription' => $exitCodeDescription
        ]);
    }

    public function feeds($locale)
    {
        return view('panel.feeds', [
            'infojobs'  => InfoJobs::getUserJobs()->get(),
            'channels'  => App\Model\Channel::all(),
            'categories'=> Category::all(),
            'sites'     => $this->commons->sites,
            'locale'    => $this->commons->locale,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
        ]);
    }

    public function site($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (Request::isMethod('post')) {
            $site->title_index = Request::input('title_index');
            $site->title_category = Request::input('title_category');
            $site->title_tag = Request::input('title_tag');
            $site->title_topscenes = Request::input('title_topscenes');

            $site->description_index = Request::input('description_index');
            $site->description_category = Request::input('description_category');
            $site->description_tag = Request::input('description_tag');
            $site->description_topscenes = Request::input('description_topscenes');

            $site->domain = Request::input('domain');
            $site->head_billboard = Request::input('head_billboard');
            $site->google_analytics = Request::input('google_analytics');
            $site->language_id = Request::input('language_id');
            $site->save();
        }

        $category_query_string = Request::input('category_query_string');

        if ($category_query_string) {
            $categories = Category::getTranslationSearch($category_query_string, $this->language->id)
                ->paginate($this->commons->perPageTags)
            ;
        } else {
            $categories = Category::paginate($this->commons->perPageTags);
        }

        return view('panel.site', [
            'site'         => $site,
            'categories'   => $categories,
            'query_string' => "",
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
            'title'        => "Admin Panel",
        ]);
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
                    'language'     => $this->commons->language,
                    'languages'    => $this->commons->languages,
                    'locale'       => $this->commons->locale,
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
            'language'     => $this->commons->language,
            'languages'    => $this->commons->languages,
            'locale'       => $this->commons->locale,
        ]);
    }

    public function deleteSite($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
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

        echo rZeBotCommons::getLogosFolder().PHP_EOL;

        $v = Validator::make(Request::all(), [
            'logo'       => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        $v->after(function($validator) {
            $extensions_acepted = array("png");
            $extension = Input::file('logo')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Logo invalid!');
            }
        });


        if (Request::hasFile('logo') && !$v->fails()) {
            Request::session()->flash('success', 'Logo uploaded successful');
            Request::file('logo')->move(rZeBotCommons::getLogosFolder(), md5($site_id).".".Request::file('logo')->getClientOriginalExtension());
        } else {
            Request::session()->flash('error', 'Upload invalid file. Check your file, size ane extension (pngs only)!');
        }

        return redirect()->route('sites', ['locale' => $this->commons->locale]);
    }

    public function updateColors($locale, $site_id)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
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

        try {
            $site->save();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return json_encode(array('status' => $status));

    }

    public function works($locale)
    {
        return view('panel.works', [
            'locale'    => $this->commons->locale,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages,
            'infojobs' => InfoJobs::getUserJobs()->paginate($this->commons->perPageJobs),
        ]);
    }
}