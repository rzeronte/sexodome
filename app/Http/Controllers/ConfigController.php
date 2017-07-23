<?php

namespace App\Http\Controllers;

use App\Jobs\ImportScenesWorker;
use App\Model\Language;
use App\Model\Type;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use App\Model\Channel;
use App\Model\Popunder;
use App\Model\Scene;
use App\Model\Category;
use App\Model\Tag;
use App\Model\CronJob;
use App\Model\CategoryTranslation;
use App\rZeBot\sexodomeKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Model\TagTranslation;
use Illuminate\Support\Facades\Log;
use App\Model\Pornstar;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('CheckVerifyUser');
        $this->middleware('auth');
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

    public function home()
    {
        return redirect()->route('sites');
    }

    public function scenes($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

        $scenes = Scene::getScenesForExporterSearch(
            $request->input('q', false),
            $request->input('tag_q'),
            $site->language->id,
            $request->input('duration'),
            $request->input('scene_id'),
            $request->input('category_string'),
            ($request->input('empty_title') == "on") ? true : false,
            ($request->input('empty_description') == "on") ? true : false,
            Auth::user()->id,
            $site->id
        );

        return view('panel.scenes', [
            'scenes' => $scenes->paginate(App::make('sexodomeKernel')->perPageScenes),
            'site'   => $site,
            'title'  => "Admin Panel",
            'sites'  => Site::where('user_id', '=', Auth::user()->id)->get(),
        ]);
    }

    public function ajaxSiteTags($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $tags = Tag::getTranslationSearch($request->input('q'), $site->language->id, $site_id)
            ->paginate(App::make('sexodomeKernel')->perPageScenes)
        ;

        return view('panel.ajax._ajax_site_tags', [
            'site' => $site,
            'tags' => $tags,
        ]);
    }

    public function ajaxSiteCategories($site_id, Request $request)
    {
        $query_string = $request->input('q');

        $site = Site::findOrFail($site_id);

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $categories = Category::getTranslationSearch(
                $query_string,
                $site->language->id,
                $site->id,
                $request->input('order_by_nscenes', false)
            )
            ->paginate(30)
        ;

        return view('panel.ajax._ajax_site_categories', [
            'site'       => $site,
            'categories' => $categories,
        ]);
    }

    public function saveTagTranslation($tag_id, Request $request)
    {
        $name = $request->input('language_' . App::make('sexodomeKernel')->language->id);

        $tagTranslation = TagTranslation::where('tag_id', $tag_id)
            ->where('language_id', App::make('sexodomeKernel')->language->id)
            ->first()
        ;

        $tagTranslation->name = $name;
        $tagTranslation->permalink = str_slug($name);
        $tagTranslation->save();

        try {
            $tag = Tag::findOrFail($tag_id);
            $tag->status = $request->input('status');
            $tag->save();

        } catch (\Exception $e) {
            return json_encode(['status' => false]);
        }

        return json_encode(['status' => true]);
    }

    public function saveCategoryTranslation($category_id, Request $request)
    {
        $category = Category::findOrFail($category_id);

        if (!(Auth::user()->id == $category->site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site = $category->site;
        $name = $request->input('language_' . $site->language->id);
        $thumb = $request->input('thumbnail');

        // Buscamos si existe otra categoría en el idioma del site con el mismo nombre
        $alreadyCategoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
            ->where('categories.site_id', $category->site->id)
            ->where('language_id', $site->language->id)
            ->where('name', 'like', $name)
            ->where('categories.status', 1)
            ->where('categories.id', '<>', $category_id)
            ->first();

        if ($alreadyCategoryTranslation) {
            return json_encode(['status' => false]);
        }

        $categoryTranslation = CategoryTranslation::where('category_id', $category_id)
            ->where('language_id', $site->language->id)
            ->first()
        ;

        $categoryTranslation->name = $name;
        $categoryTranslation->permalink = str_slug($name);
        $categoryTranslation->thumb = $thumb;
        $categoryTranslation->save();

        $category->status = $request->input('status');
        $category->save();

        return json_encode(['status' => true]);
    }

    public function saveTranslation($scene_id, Request $request)
    {
        $scene = Scene::findOrFail($scene_id);

        if (!(Auth::user()->id == $scene->site->user->id)) {
            abort(401, "Unauthorized");
        }

        $sceneTranslation = SceneTranslation::where('scene_id', $scene_id)
            ->where('language_id', App::make('sexodomeKernel')->language->id)
            ->first()
        ;

        $scene->thumb_index = $request->input('selectedThumb', null);
        $scene->save();

        if ($sceneTranslation) {

            $sceneTranslation->title = $request->input('title');
            $sceneTranslation->permalink = str_slug($request->input('title'));
            $sceneTranslation->description = $request->input('description');
            $sceneTranslation->save();

            return json_encode([
                'description' => $sceneTranslation->description,
                'scene_id'    => $scene_id,
                'status'      => true
            ]);
        } else {
            return json_encode(['status' => false]);
        }
    }

    public function ajaxSitePornstars($site_id)
    {
        $site = Site::findOrFail($site_id);

        $pornstars = Pornstar::where('site_id', '=', $site_id)->paginate(App::make('sexodomeKernel')->perPagePanelPornstars);

        return view('panel.ajax._ajax_site_pornstars', [
            'site'      => $site,
            'pornstars' => $pornstars,
        ]);
    }

    public function scenePreview($scene_id)
    {
        $scene = Scene::findOrFail($scene_id);

        if (!(Auth::user()->id == $scene->site->user->id)) {
            abort(401, "Unauthorized");
        }

        return view('panel.ajax._ajax_preview', [
            'title' => "Admin Panel",
            'scene' => $scene
        ]);
    }

    public function fetch($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $tags = $request->input('tags', false);
        if (strlen($tags) == 0) {
            $tags = 'false';
        }

        $queueParams = [
            'feed_name'  => $request->input('feed_name'),
            'site_id'    => $request->input('site_id'),
            'max'        => $request->input('max'),
            'duration'   => $request->input('duration'),
            'tags'       => $tags,
        ];

        $queueParams['only_with_pornstars'] = 'false';
        if ($request->input('only_with_pornstars') == 1) {
            $queueParams['only_with_pornstars'] = 'true';
        }

        try {
            $job = new ImportScenesWorker($queueParams);
            dispatch($job);

            return json_encode(['status' => true]);

        } catch (\Exception $e) {
            Log::error("[fetch siteid: $site_id] " . $e->getMessage());
            return json_encode(['status' => false]);
        }
    }

    public function sites()
    {
        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff . " -30 days"));

        $sites = Auth::user()->getSites();

        return view('panel.sites', [
            'channels' => Channel::all(),
            'title'    => "Admin Panel",
            'sites'    => $sites,
            'fi'       => $fi,
            'ff'       => $ff,
        ]);
    }

    public function site($site_id)
    {
        $site = Site::findOrFail($site_id);

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        // Si el idioma es distinto, actualizamos locale e idioma
        if ($site->language->code != App::getLocale()) {
            App::setLocale($site->language->code);
            App::make('sexodomeKernel')->language = $site->language;
        }

        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff . " -50 days"));

        return view('panel.site', [
            'channels'  => Channel::all(),
            'title'     => "Admin Panel",
            'site'      => $site,
            'sites'     => Auth::user()->getSites(),
            'fi'        => $fi,
            'ff'        => $ff,
            'types'     => Type::all(),
        ]);
    }

    public function sceneThumbs($scene_id)
    {
        return view('panel.ajax._ajax_scene_thumbs', ['scene' => Scene::findOrFail($scene_id)]);
    }

    public function ajaxCronJobs($site_id)
    {
        return view('panel.ajax._ajax_site_cronjobs', [
            'site'  => Site::findOrFail($site_id),
            'scene' => Scene::findOrFail($site_id),
        ]);
    }

    public function updateSiteSEO($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

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

        $site->type_id = $request->input('type_id');

        $site->logo_h1 = $request->input('logo_h1');
        $site->categories_h3 = $request->input('categories_h3');
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
        $site->header_text = $request->input('header_text', "");
        $site->link_billboard = $request->input('link_billboard');
        $site->link_billboard_mobile = $request->input('link_billboard_mobile');

        $site->google_analytics = $request->input('google_analytics');

        $site->javascript = $request->input('javascript');

        $site->save();

        return json_encode(['status' => true]);
    }

    public function addSite(Request $request)
    {
        $sites = Site::where('user_id', '=', Auth::user()->id)->get();

        if ($request->isMethod('post')) {

            // check if already exists
            $site = Site::where('domain', '=', trim($request->input('domain')))->first();

            // if exists return with custom error
            if ($site) {
                $request->session()->flash('error_domain', 'Domain <' . trim($request->input('domain')) . '> already exists!');

                return view('panel.add_site', ['sites' => $sites]);
            }

            // create new site for current user
            $newSite = new Site();
            $newSite->user_id = Auth::user()->id;
            $newSite->name = $request->input('subdomain');
            $newSite->language_id = env("DEFAULT_FETCH_LANGUAGE", 2);
            $newSite->domain = $request->input('domain');
            $newSite->have_domain = 1;
            $newSite->header_text = "";
            $newSite->save();

            return redirect()->route('site', ['site_id' => $newSite->id]);
        }

        return view('panel.add_site', ['sites' => $sites]);
    }

    public function deleteSite($site_id)
    {
        $site = Site::findOrFail($site_id);

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->delete();

        return redirect()->route('sites', []);
    }

    public function checkSubdomain(Request $request)
    {
        if (strlen($request->input('subdomain')) == 0) {
            abort(406, 'Not acceptable');
        }

        $sites = Site::where('name', '=', $request->input('subdomain'))->count();

        return json_encode(['status' => ($sites == 0) ? true : false]);
    }

    public function checkDomain(Request $request)
    {
        if (strlen($request->input('domain')) == 0) {
            abort(404, 'Not allowed');
        }

        $sites = Site::where('domain', '=', $request->input('domain'))->count();

        return json_encode(['status' => ($sites == 0) ? true : false]);
    }

    public function updateGoogleData($site_id, Request $request)
    {
        try {
            $site = Site::findOrFail($site_id);
            $site->ga_account = $request->input('ga_view_' . $site->id);
            $site->save();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return json_encode(['status' => $status]);
    }

    public function updateIframeData($site_id, Request $request)
    {
        try {
            $site = Site::findOrFail($site_id);
            $site->iframe_site_id = ($request->input('iframe_site_id_' . $site->id) != "") ? $request->input('iframe_site_id_' . $site->id) : null;
            $site->save();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return json_encode(['status' => $status]);
    }

    public function updateLogo($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

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
            $extensions_acepted = ["png"];
            $extension = Input::file('logo')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Logo invalid!');
            }
        });

        $vF->after(function ($validator) {
            $extensions_acepted = ["png"];
            $extension = Input::file('favicon')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Logo invalid!');
            }
        });

        $vH->after(function ($validator) {
            $extensions_acepted = ["png"];
            $extension = Input::file('header')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                Request::session()->flash('error', 'Header invalid!');
            }
        });

        if ($request->hasFile('logo') && !$v->fails()) {
            $request->session()->flash('success', 'Logo uploaded successful');
            $request->file('logo')->move(sexodomeKernel::getLogosFolder(), md5($site_id) . "." . $request->file('logo')->getClientOriginalExtension());
        } else {
            $request->session()->flash('error', 'Upload invalid file. Check your Logo file, size ane extension (pngs only)!');
        }

        if ($request->hasFile('favicon') && !$vF->fails()) {
            $request->session()->flash('success', 'Logo uploaded successful');
            $request->file('favicon')->move(sexodomeKernel::getFaviconsFolder(), md5($site_id) . "." . $request->file('favicon')->getClientOriginalExtension());
        } else {
            $request->session()->flash('error', 'Upload invalid file. Check your Favicon file, size ane extension (pngs only)!');
        }

        if ($request->hasFile('header') && !$vH->fails() && $delete_header != 1) {
            $request->session()->flash('success', 'Header uploaded successful');
            $request->file('header')->move(sexodomeKernel::getHeadersFolder(), md5($site_id) . "." . $request->file('header')->getClientOriginalExtension());
        } else {
            if ($delete_header == 1) {
                unlink(sexodomeKernel::getHeadersFolder() . md5($site_id) . ".png");
            } else {
                $request->session()->flash('error', 'Upload invalid file. Check your Header file, size ane extension (png only)!');
            }
        }

        return redirect()->route('site', ['site_id' => $site->id]);
    }

    public function updateColors($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->color   = $request->input('color') != "" ? $request->input('color') : null;
        $site->color2  = $request->input('color2') != "" ? $request->input('color2') : null;
        $site->color3  = $request->input('color3') != "" ? $request->input('color3') : null;
        $site->color4  = $request->input('color4') != "" ? $request->input('color4') : null;
        $site->color5  = $request->input('color5') != "" ? $request->input('color5') : null;
        $site->color6  = $request->input('color6') != "" ? $request->input('color6') : null;
        $site->color7  = $request->input('color7') != "" ? $request->input('color7') : null;
        $site->color8  = $request->input('color8') != "" ? $request->input('color8') : null;
        $site->color9  = $request->input('color9') != "" ? $request->input('color9') : null;
        $site->color10 = $request->input('color10') != "" ? $request->input('color10') : null;
        $site->color11 = $request->input('color11') != "" ? $request->input('color11') : null;
        $site->color12 = $request->input('color12') != "" ? $request->input('color12') : null;
        $site->save();

        Artisan::call('zbot:css:update', ['--site_id' => $site->id]);

        return json_encode(['status' => true]);
    }

    public function ajaxSaveCronJob(Request $request)
    {
        $channel = Channel::where('name', $request->input('feed_name'))->firstOrFail();

        $tags = $request->input('tags', false);

        if (strlen($tags) == 0) {
            $tags = 'false';
        }

        $queueParams = [
            'feed_name' => $request->input('feed_name'),
            'site_id'   => $request->input('site_id'),
            'max'       => $request->input('max'),
            'duration'  => $request->input('duration'),
            'tags'      => $tags,
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

        return json_encode(['status' => $status]);
    }

    public function deleteCronJob($cronjob_id)
    {
        try {
            $cronjob = CronJob::findOrFail($cronjob_id);

            if (!(Auth::user()->id == $cronjob->site->user->id)) {
                abort(401, "Unauthorized");
            }

            $cronjob->delete();
            $status = true;
        } catch(\Exception $e) {
            $status = false;
        }

        return json_encode(['status' => $status]);
    }

    public function ajaxPopunders($site_id)
    {
        $site = Site::findOrFail($site_id);

        return view('panel.ajax._ajax_site_popunders', ['popunders' => $site->popunders()->get()]);
    }

    public function ajaxSavePopunder($site_id, Request $request)
    {
        try {
            $site = Site::findOrFail($site_id);
            $newPopunder = new Popunder();
            $newPopunder->url = $request->input('url', false);
            $newPopunder->site_id = $site->id;
            $newPopunder->save();
            return json_encode(['status' => $status = true]);
        } catch (\Exception $e) {
            return json_encode(['status' => $status = false]);
        }
    }

    public function ajaxDeletePopunder($popunder_id)
    {
        try {
            $popunder = Popunder::findOrFail($popunder_id);
            $popunder->delete();
            return json_encode(['status' => $status = true]);
        } catch (\Exception $e) {
            return json_encode(['status' => $status = false]);
        }
    }

    public function categoryThumbs($category_id)
    {
        $category = Category::findOrFail($category_id);

        $site_type_id = $category->site->type_id;

        if ($site_type_id == App::make('sexodomeKernel')->sex_types['straigth']) {
            $files = File::allFiles(public_path()."/categories_market");
        } else {
            $files = File::allFiles(public_path()."/categories_market_gay");
        }

        $filenames = [];
        foreach ($files as $file) {
            $filenames[] = $file->getFilename();
        }

        return view('panel.ajax._ajax_category_thumbs', [
            'category'  => $category,
            'filenames' => $filenames,
        ]);
    }

    public function categoryUnlock($category_translation_id)
    {
        $categoryTranslation = CategoryTranslation::find($category_translation_id);

        if (!$categoryTranslation) {
            $status = false;
        } else {
            $status = true;
            $categoryTranslation->thumb_locked = NULL;
            $categoryTranslation->save();
        }

        return json_encode(['status' => $status]);
    }

    public function uploadCategory($category_id, Request $request)
    {
        $category = Category::findOrFail($category_id);

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

        $data = ["files" => [[
            "category_id" => $category_id,
            "name"        => $fileName,
            "url"         => $final_url,
        ]]];

        return json_encode($data);
    }

    public function orderCategories($site_id, Request $request)
    {
        if ($request->input('o') != "") {

            foreach ($request->input('o') as $category) {
                $categoyBBDD = Category::find($category['i']);
                $categoyBBDD->cache_order = -1 * $category['o'];
                $categoyBBDD->save();
            }

            return json_encode(['status' => true]);
        }

        $site = Site::findOrFail($site_id);

        DB::table('categories')->where('site_id', $site->id)->update(['cache_order' => -999999]);

        $categories = Category::getForTranslation(
                $status = true,
                $site->id,
                App::make('sexodomeKernel')->language->id,
                $limit = 40
            )
            ->get()
        ;

        return view('panel.categories_order', [
            'sites'      => Auth::user()->getSites(),
            'site'       => Site::find($site_id),
            'categories' => $categories
        ]);
    }

    public function categoryTags($category_id, Request $request)
    {
        $category = Category::findOrFail($category_id);

        if ($request->isMethod('post')) {
            $categories_ids = $request->input('categories');
            $category->tags()->sync($categories_ids);

            return json_encode(['status' => 1]);
        }

        $category_tags = Tag::getTranslationByCategory($category, 2)->get()->pluck('id');
        $category_tags = $category_tags->all();

        $site_tags = Tag::getTranslationSearch(false, 2, $category->site->id)->orderBy('permalink', 'asc')->get();

        return view('panel.ajax._ajax_category_tags', [
            'category'      => $category,
            'category_tags' => $category_tags,
            'tags'          => $site_tags,
        ]);
    }

    public function createCategory($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

        // Si venimos por post, devolvemos resultado por json en lugar del twig.
        if ($request->isMethod('post')) {
            $newCategory = new Category();
            $newCategory->site_id = $site->id;
            $newCategory->status = 0;
            $newCategory->text = $request->input('language_en');
            $newCategory->save();

            foreach(Language::getAddLanguages($site->language_id) as $language) {
                $newCategoryTranslation = new CategoryTranslation();
                $newCategoryTranslation->category_id = $newCategory->id;
                $newCategoryTranslation->language_id = $language->id;
                $newCategoryTranslation->name = $request->input('language_'.$language->code);
                $newCategoryTranslation->permalink = rZeBotUtils::slugify($request->input('language_'.$language->code));
                $newCategoryTranslation->save();
            }
            return json_encode(['status' => true]);
        } else {
            return view('panel.ajax._ajax_site_create_category', ['site' => $site]);
        }
    }

    public function createTag($site_id, Request $request)
    {
        $site = Site::findOrFail($site_id);

        // Si venimos por post, devolvemos resultado por json en lugar del twig.
        if ($request->isMethod('post')) {
            $newTag = new Tag();
            $newTag->site_id = $site->id;
            $newTag->status = 1;
            $newTag->save();

            foreach(Language::getAddLanguages($site->language_id) as $language) {
                $newTagTranslation = new TagTranslation();
                $newTagTranslation->tag_id = $newTag->id;
                $newTagTranslation->language_id = $language->id;
                $newTagTranslation->name = $request->input('language_'.$language->code);
                $newTagTranslation->permalink = rZeBotUtils::slugify($request->input('language_'.$language->code));
                $newTagTranslation->save();
            }
            return json_encode(['status' => true]);
        } else {
            return view('panel.ajax._ajax_site_create_tag', ['site' => $site]);
        }
    }

    public function ajaxDeleteCategory($category_id)
    {
        try {
            $category = Category::findOrFail($category_id);
            $category->delete();
            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false]);
        }
    }

    public function ajaxDeleteTag($tag_id)
    {
        try {
            $tag = Tag::findOfFail($tag_id);
            $tag->delete();
            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false]);
        }
    }

    public function ajaxDeleteScene($scene_id)
    {
        try {
            $scene = Scene::findOrFail($scene_id);
            $scene->delete();
            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}