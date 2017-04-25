<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\rZeBot\rZeBotCommons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Model\Site;
use App\Model\Channel;
use App\Model\Popunder;
use App\Model\Scene;
use App\Model\Category;
use App\Model\Tag;
use App\Model\CronJob;
use App\Model\InfoJobs;
use App\Model\CategoryTranslation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

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

    public function scenes(Request $request)
    {
        $query_string = $request->input('q');
        $tag_query_string = $request->input('tag_q');
        $publish_for = $request->input('publish_for');  //site_id or 'notpublished'
        $duration = $request->input('duration');
        $scene_id = $request->input('scene_id');
        $category_string = $request->input('category_string');
        $empty_title = ($request->input('empty_title') == "on") ? true : false;
        $empty_description = ($request->input('empty_description') == "on") ? true : false;

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
            'scenes' => $scenes->orderBy('scenes.id', 'desc')->paginate($this->commons->perPageScenes),
            'query_string' => $query_string,
            'tag_q' => $tag_query_string,
            'publish_for' => $publish_for,
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale,
            'title' => "Admin Panel",
            'sites' => $sites,
            'duration' => $duration,
        ]);
    }

    public function ajaxSiteTags($locale, $site_id, Request $request)
    {
        $query_string = $request->input('q');

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
            'site' => $site,
            'tags' => $tags,
            'locale' => $this->commons->locale,
            'language' => $this->commons->language
        ]);
    }

    public function ajaxSiteCategories($locale, $site_id, Request $request)
    {
        $query_string = $request->input('q');

        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $order_by_nscenes = $request->input('order_by_nscenes', false);

        $categories = Category::getTranslationSearch($query_string, $this->commons->language->id, $site->id, $order_by_nscenes)
            ->paginate($this->commons->perPageScenes);

        return view('panel.ajax._ajax_site_categories', [
            'site' => $site,
            'categories' => $categories,
            'locale' => $this->commons->locale,
            'language' => $this->commons->language
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
            'site' => $site,
            'locale' => $this->commons->locale,
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'infojobs' => $infojobs,
        ]);
    }

    public function saveTagTranslation($locale, $tag_id, Request $request)
    {
        $name = $request->input('language_' . $this->commons->language->id);

        $tagTranslation = TagTranslation::where('tag_id', $tag_id)
            ->where('language_id', $this->commons->language->id)
            ->first();

        $tagTranslation->name = $name;
        $tagTranslation->permalink = str_slug($name);
        $tagTranslation->save();

        $tag = Tag::find($tag_id);
        $tag->status = $request->input('status');
        $tag->save();

        // response json if ajax request
        return json_encode(array('status' => 1));
    }

    public function saveCategoryTranslation($locale, $category_id, Request $request)
    {
        $category = Category::find($category_id);

        if (!$category) {
            abort(404, "Category not found");
        }

        if (!(Auth::user()->id == $category->site->user->id)) {
            abort(401, "Unauthorized");
        }
        $name = $request->input('language_' . $this->commons->language->id);
        $thumb = $request->input('thumbnail');

        // Buscamos si existe otra categoría en el idioma utilizado con el mismo nombre
        $alreadyCategoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
            ->where('categories.site_id', $category->site->id)
            ->where('language_id', $this->commons->language->id)
            ->where('name', 'like', $name)
            ->where('categories.status', 1)
            ->where('categories.id', '<>', $category_id)
            ->first();

        if ($alreadyCategoryTranslation) {
            return json_encode(array('status' => 0));
        }

        $categoryTranslation = CategoryTranslation::where('category_id', $category_id)
            ->where('language_id', $this->commons->language->id)
            ->first();

        $categoryTranslation->name = $name;
        $categoryTranslation->permalink = str_slug($name);
        $categoryTranslation->thumb_locked = 1;
        $categoryTranslation->thumb = $thumb;
        $categoryTranslation->save();

        $category->status = $request->input('status');
        $category->save();

        return json_encode(array('status' => 1));
    }

    public function changeLocale($locale)
    {
        App::setLocale($locale);

        return redirect()->route('content', ['locale' => $this->commons->locale]);
    }

    public function saveTranslation($locale, $scene_id, Request $request)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort(404, "Scene not found");
        }

        if (!(Auth::user()->id == $scene->site->user->id)) {
            abort(401, "Unauthorized");
        }

        $title = $request->input('title');
        $description = $request->input('description');
        $selectedThumb = $request->input('selectedThumb', null);

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

            return json_encode(array(
                'description' => $sceneTranslation->description,
                'scene_id' => $scene_id,
                'status' => 1
            ));
        } else {
            return json_encode(array('status' => 0));
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
            'site' => $site,
            'pornstars' => $pornstars,
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale
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
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale,
            'title' => "Admin Panel",
            'scene' => $scene
        ]);

    }

    public function fetch($site_id, Request $request)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(401, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $channel = Channel::where('name', '=', $request->input('feed_name'))->first();

        if (!$channel) {
            abort(404, "Channel not found");
        }

        // categories y tags son 'false' en string, por requisito del comando (refact)
        $categories = $request->input('categories', false);
        if (strlen($categories) == 0) {
            $categories = 'false';
        }

        $tags = $request->input('tags', false);
        if (strlen($tags) == 0) {
            $tags = 'false';
        }
        $queueParams = [
            'feed_name' => $request->input('feed_name'),
            'site_id' => $request->input('site_id'),
            'max' => $request->input('max'),
            'duration' => $request->input('duration'),
            'tags' => $tags,
            'categories' => $categories,
        ];

        if ($request->input('only_with_pornstars') == 1) {
            $queueParams['only_with_pornstars'] = 'true';
        } else {
            $queueParams['only_with_pornstars'] = 'false';
        }

        $newInfoJob = new InfoJobs();
        $newInfoJob->site_id = $site_id;
        $newInfoJob->feed_id = $channel->id;
        $newInfoJob->created_at = date("Y:m:d H:i:s");
        $newInfoJob->serialized = json_encode($queueParams);
        $newInfoJob->save();

        $queueParams['job'] = $newInfoJob->id;

        try {
            $job = (new importScenesFromFeed($queueParams));
            $this->dispatch($job);

            return json_encode(['status' => true]);

        } catch (\Exception $e) {
            Log::info('[ERROR Al lanzar importScenesFromFeed]');

            return json_encode(['status' => false]);
        }

        return json_encode(['status' => false]);
    }

    public function sites()
    {
        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff . " -30 days"));

        $sites = Site::where('user_id', '=', Auth::user()->id)
            ->orderBy('language_id', 'asc')
            ->get();

        return view('panel.sites', [
            'channels' => Channel::all(),
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale,
            'title' => "Admin Panel",
            'sites' => $sites,
            'fi' => $fi,
            'ff' => $ff,
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
        $fi = date("Y-m-d", strtotime($ff . " -50 days"));

        return view('panel.site', [
            'channels' => Channel::all(),
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale,
            'title' => "Admin Panel",
            'site' => $site,
            'sites' => Site::where('user_id', '=', Auth::user()->id)->get(),
            'fi' => $fi,
            'ff' => $ff,
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

        $keywords = LaravelAnalyticsFacade::setSiteId('ga:' . $site->ga_account)->getTopKeyWords(90, $maxResults = 30);

        return view('panel.ajax._ajax_site_keywords', [
            'keywords' => $keywords,
            'language' => $this->commons->language,
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

        $referrers = LaravelAnalyticsFacade::setSiteId('ga:' . $site->ga_account)->getTopReferrers(90, $maxResults = 30);

        return view('panel.ajax._ajax_site_referrers', [
            'referrers' => $referrers,
            'language' => $this->commons->language,
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

        $pageViews = LaravelAnalyticsFacade::setSiteId('ga:' . $site->ga_account)->getMostVisitedPages(90, $maxResults = 30);

        return view('panel.ajax._ajax_site_pageviews', [
            'pageViews' => $pageViews,
            'language' => $this->commons->language,
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
            'scene' => $scene,
            'language' => $this->commons->language,
            'languages' => $this->commons->languages
        ]);
    }

    public function ajaxCronJobs($locale, $site_id)
    {
        $site = Site::find($site_id);
        $scene = Scene::find($site_id);

        return view('panel.ajax._ajax_site_cronjobs', [
            'site' => $site,
            'scene' => $scene,
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale
        ]);

    }

    public function updateSiteSEO($locale, $site_id, Request $request)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        // Check site is from user
        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->status = $request->input('status');

        $site->language_id = $request->input('language_id');

        $site->category_url = $request->input('category_url');
        $site->pornstars_url = $request->input('pornstars_url');
        $site->pornstar_url = $request->input('pornstar_url');
        $site->video_url = $request->input('video_url');

        $site->contact_email = $request->input('contact_email');

        $site->logo_h1 = $request->input('logo_h1');
        $site->h2_home = $request->input('h2_home');
        $site->h2_category = $request->input('h2_category');
        $site->h2_pornstars = $request->input('h2_pornstars');
        $site->h2_pornstar = $request->input('h2_pornstar');

        $site->title_index = $request->input('title_index');
        $site->title_category = $request->input('title_category');

        $site->description_index = $request->input('description_index');
        $site->description_category = $request->input('description_category');

        $site->title_pornstars = $request->input('title_pornstars');
        $site->title_pornstar = $request->input('title_pornstar');

        $site->description_pornstars = $request->input('description_pornstars');
        $site->description_pornstar = $request->input('description_pornstar');

        $site->domain = $request->input('domain');
        $site->head_billboard = $request->input('head_billboard');
        $site->link_billboard = $request->input('link_billboard');
        $site->google_analytics = $request->input('google_analytics');

        $site->banner_script1 = $request->input('banner_script1');
        $site->banner_script2 = $request->input('banner_script2');
        $site->banner_script3 = $request->input('banner_script3');

        $site->banner_mobile1 = $request->input('banner_mobile1');

        $site->banner_video1 = $request->input('banner_video1');
        $site->banner_video2 = $request->input('banner_video2');

        $site->button1_url = $request->input('button1_url');
        $site->button2_url = $request->input('button2_url');
        $site->button1_text = $request->input('button1_text');
        $site->button2_text = $request->input('button2_text');

        $site->save();

        return json_encode(array('status' => true));
    }

    public function removeCategory($locale, $category_id, $site_id, Request $request)
    {
        $siteCategory = SiteCategory::where('category_id', $category_id)->where('site_id', $site_id)->first();
        $siteCategory->delete();

        return redirect()->route('site', ['locale' => $this->locale, $site_id]);
    }

    public function addCategory($locale, $category_id, $site_id, Request $request)
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
            'locale' => $this->commons->locale,
            'site_id' => $site_id,
            'page' => $request->input('page')
        ]);
    }

    public function addSite($locale, Request $request)
    {
        $sites = Site::where('user_id', '=', Auth::user()->id)->get();

        if ($request->isMethod('post')) {

            // check if already exists
            if ($request->input('type_site') == 1) {
                $site = Site::where('domain', '=', trim($request->input('domain')))->first();
            } else {
                $site = Site::where('name', '=', trim($request->input('subdomain')))->first();
            }

            // if exists return with custom error
            if ($site) {
                if ($request->input('type_site') == 1) {
                    Request::session()->flash('error_domain', 'Domain <' . trim($request->input('domain')) . '> already exists!');
                } else {
                    Request::session()->flash('error_subdomain', 'Subdomain <' . trim($request->input('subdomain')) . '> already exists!');
                }

                return view('panel.add_site', [
                    'language' => $this->commons->language,
                    'languages' => $this->commons->languages,
                    'locale' => $this->commons->locale,
                    'sites' => $sites
                ]);
            }

            // create new site for current user
            $newSite = new Site();
            $newSite->user_id = Auth::user()->id;
            $newSite->name = $request->input('subdomain');
            $newSite->language_id = env("DEFAULT_FETCH_LANGUAGE", 2);
            $newSite->domain = $request->input('domain');
            $newSite->have_domain = $request->input('type_site');

            $newSite->save();

            if ($newSite->have_domain == 0) {
                // Alta del subdominio en CF
                $clientCF = new CF($this->commons->cloudFlareCfg['email'], $this->commons->cloudFlareCfg['key']);
                try {
                    $subdomain = $request->input('subdomain');

                    $clientCF->rec_new(array(
                        'z' => $this->commons->cloudFlareCfg['zone'],
                        'name' => $subdomain . "." . $this->commons->cloudFlareCfg['zone'],
                        'ttl' => 1,
                        'type' => 'A',
                        'content' => $this->commons->cloudFlareCfg['ip']
                    ));

                    return redirect()->route('sites', [
                        'locale' => $this->commons->locale
                    ]);

                } catch (CFException $e) {
                    Request::session()->flash('error_subdomain', '(DNS) subdomain <' . $request->input('subdomain') . '> already exists!');
                }
            } else {
                return redirect()->route('sites', [
                    'locale' => $this->commons->locale
                ]);
            }
        }

        return view('panel.add_site', [
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale,
            'sites' => $sites
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

    public function checkSubdomain($locale, Request $request)
    {
        $subdomain = $request->input('subdomain');

        if (strlen($subdomain) == 0) {
            abort(404, 'Not allowed');
        }

        $sites = Site::where('name', '=', $subdomain)->count();

        if ($sites == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        return json_encode(array('status' => $status));
    }

    public function checkDomain($locale, Request $request)
    {
        $domain = $request->input('domain');

        if (strlen($domain) == 0) {
            abort(404, 'Not allowed');
        }

        $sites = Site::where('domain', '=', $domain)->count();

        if ($sites == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        return json_encode(array('status' => $status));
    }

    public function updateGoogleData($locale, $site_id, Request $request)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $site->ga_account = $request->input('ga_view_' . $site->id);

        try {
            $site->save();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return json_encode(array('status' => $status));
    }

    public function updateIframeData($locale, $site_id, Request $request)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $site->iframe_site_id = ($request->input('iframe_site_id_' . $site->id) != "") ? $request->input('iframe_site_id_' . $site->id) : null;

        try {
            $site->save();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return json_encode(array('status' => $status));
    }

    public function updateLogo($locale, $site_id, Request $request)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $delete_header = $request->input('header_delete');

        // logo validator
        $v = Validator::make($request->all(), [
            'logo' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // favicon validator
        $vF = Validator::make($request->all(), [
            'favicon' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // favicon validator
        $vH = Validator::make($request->all(), [
            'header' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        $v->after(function ($validator) {
            $extensions_acepted = array("png");
            $extension = Input::file('logo')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Logo invalid!');
            }
        });

        $vF->after(function ($validator) {
            $extensions_acepted = array("png");
            $extension = Input::file('favicon')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Logo invalid!');
            }
        });

        $vH->after(function ($validator) {
            $extensions_acepted = array("png");
            $extension = Input::file('header')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Header invalid!');
            }
        });

        if ($request->hasFile('logo') && !$v->fails()) {
            $request->session()->flash('success', 'Logo uploaded successful');
            $request->file('logo')->move(rZeBotCommons::getLogosFolder(), md5($site_id) . "." . $request->file('logo')->getClientOriginalExtension());
        } else {
            $request->session()->flash('error', 'Upload invalid file. Check your Logo file, size ane extension (pngs only)!');
        }

        if ($request->hasFile('favicon') && !$vF->fails()) {
            $request->session()->flash('success', 'Logo uploaded successful');
            $request->file('favicon')->move(rZeBotCommons::getFaviconsFolder(), md5($site_id) . "." . $request->file('favicon')->getClientOriginalExtension());
        } else {
            $request->session()->flash('error', 'Upload invalid file. Check your Favicon file, size ane extension (pngs only)!');
        }

        if ($request->hasFile('header') && !$vH->fails() && $delete_header != 1) {
            $request->session()->flash('success', 'Header uploaded successful');
            $request->file('header')->move(rZeBotCommons::getHeadersFolder(), md5($site_id) . "." . $request->file('header')->getClientOriginalExtension());
        } else {
            if ($delete_header == 1) {
                unlink(rZeBotCommons::getHeadersFolder() . md5($site_id) . ".png");
            } else {
                $request->session()->flash('error', 'Upload invalid file. Check your Header file, size ane extension (png only)!');
            }
        }

        return redirect()->route('site', ['locale' => $this->commons->locale, 'site_id' => $site->id]);
    }

    public function updateColors($locale, $site_id, Request $request)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->color = ($request->input('color') != "") ? $request->input('color') : null;
        $site->color2 = ($request->input('color2') != "") ? $request->input('color2') : null;
        $site->color3 = ($request->input('color3') != "") ? $request->input('color3') : null;
        $site->color4 = ($request->input('color4') != "") ? $request->input('color4') : null;
        $site->color5 = ($request->input('color5') != "") ? $request->input('color5') : null;
        $site->color6 = ($request->input('color6') != "") ? $request->input('color6') : null;
        $site->color7 = ($request->input('color7') != "") ? $request->input('color7') : null;
        $site->color8 = ($request->input('color8') != "") ? $request->input('color8') : null;
        $site->color9 = ($request->input('color9') != "") ? $request->input('color9') : null;
        $site->color10 = ($request->input('color10') != "") ? $request->input('color10') : null;
        $site->color11 = ($request->input('color11') != "") ? $request->input('color11') : null;
        $site->color12 = ($request->input('color12') != "") ? $request->input('color12') : null;

        $site->save();
        $status = true;

        Artisan::call('zbot:css:update', [
            '--site_id' => $site->id
        ]);

        return json_encode(array('status' => $status));
    }

    public function ajaxSaveCronJob($locale, Request $request)
    {
        $channel = Channel::where('name', $request->input('feed_name'))->first();

        if (!$channel) {
            abort(404, "Channel not found");
        }

        $tags = $request->input('tags', false);

        if (strlen($tags) == 0) {
            $tags = 'false';
        }

        $queueParams = [
            'feed_name' => $request->input('feed_name'),
            'site_id' => $request->input('site_id'),
            'max' => $request->input('max'),
            'duration' => $request->input('duration'),
            'tags' => $tags,
        ];

        if ($request->input('only_with_pornstars') == 1) {
            $queueParams['only_with_pornstars'] = 'true';
        } else {
            $queueParams['only_with_pornstars'] = 'false';
        }

        $cronjob = new CronJob();
        $cronjob->site_id = $request->input('site_id');
        $cronjob->channel_id = $channel->id;
        $cronjob->params = json_encode($queueParams);
        $cronjob->save();

        $status = true;

        return json_encode(array('status' => $status));
    }

    public function deleteCronJob($locale, $cronjob_id)
    {
        $cronjob = CronJob::find($cronjob_id);

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
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale,
        ]);
    }

    public function ajaxSavePopunder($locale, $site_id, Request $request)
    {
        $site = Site::find($site_id);

        if (!$site) {
            abort(404, "Site not found");
        }

        $url = $request->input('url', false);

        $newPopunder = new Popunder();
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

        $popunder->delete();

        return json_encode(array('status' => $status = true));
    }

    public function categoryThumbs($locale, $category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            abort(404, "Category not found");
        }

        $files = File::allFiles(public_path()."/categories_market");

        $filenames = [];
        foreach ($files as $file)
        {
            $filenames[] = $file->getFilename();
        }

        return view('panel.ajax._ajax_category_thumbs', [
            'category'  => $category,
            'filenames' => $filenames,
            'language'  => $this->commons->language,
            'languages' => $this->commons->languages
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

    public function uploadCategory($category_id, Request $request)
    {
        $category = Category::find($category_id);

        if (!$category) {
            abort(404, "Category not found");
        }

        // logo validator
/*        $v = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,jpeg',      // max=50*1024; min=3*1024
        ]);

        if ($v->fails()) {
            $data = ["error" => "Upload invalid file. Check your file, size ane extension (JPG only)!"];

            return json_encode($data);
        }
*/
        $fileName = md5(microtime() . $category_id) . ".jpg";

        $final_url = "http://" . $category->site->getHost() . "/categories_custom/" . $fileName;

        $destinationPath = public_path()."/categories_custom/";

        // lock category thumbnail
        foreach($category->translations()->get() as $translation) {
            $translation->thumb_locked = 1;
            $translation->thumb = $final_url;
            $translation->save();
        }

        $request->file('file')->move($destinationPath, $fileName);

        $data = ["files" => [
            [
                "category_id" => $category_id,
                "name"        => $fileName,
                "url"         => $final_url,
            ]
        ]];

        return json_encode($data);
    }

    public function orderCategories($locale, $site_id, Request $request)
    {
        $sites = Site::where('user_id', '=', Auth::user()->id)
            ->orderBy('language_id', 'asc')
            ->get();

        if ($request->input('o') != "") {

            foreach ($request->input('o') as $category) {
                $categoyBBDD = Category::find($category['i']);
                $categoyBBDD->cache_order = -1 * $category['o'];
                $categoyBBDD->save();
            }

            return json_encode(['status' => true]);
        }

        $site = Site::find($site_id);

        DB::table('categories')->where('site_id', $site->id)->update(['cache_order' => -999999]);

        $categories = Category::getTranslationByStatus(1, $this->commons->language->id)
            ->where('site_id', '=', $site->id)
            ->orderBy('categories.cache_order', 'DESC')
            ->orderBy('categories.nscenes', 'DESC')
            ->limit(40)
            ->get();

        return view('panel.categories_order', [
            'sites' => $sites,
            'site' => Site::find($site_id),
            'language' => $this->commons->language,
            'languages' => $this->commons->languages,
            'locale' => $this->commons->locale,
            'categories' => $categories
        ]);
    }

    public function categoryTags($locale, $category_id, Request $request)
    {
        $category = Category::find($category_id);

        if (!$category) {
            abort(404, "Category not found");
        }

        if (Request::isMethod('post')) {
            $categories_ids = $request->input('categories');
            $category->tags()->sync($categories_ids);

            if (Request::ajax()) {
                return json_encode(array('status' => 1));
            }

        }
        $tags = $category->tags()->get();

        $category_tags = Tag::getTranslationByCategory($category, 2)->get()->pluck('id');
        $category_tags = $category_tags->all();

        $site_tags = Tag::getTranslationSearch(false, 2, $category->site->id)
            ->orderBy('permalink', 'asc')
            ->get();

        return view('panel.ajax._ajax_category_tags', [
            'category' => $category,
            'category_tags' => $category_tags,
            'tags' => $site_tags,
            'locale' => $this->commons->locale
        ]);
    }
}
