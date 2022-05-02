<?php

namespace App\Http\Controllers;

use Sexodome\SexodomeApi\Application\CategoryUnlockCommandHandler;
use Sexodome\SexodomeApi\Application\CheckDomainCommandHandler;
use Sexodome\SexodomeApi\Application\CreateCategoryCommandHandler;
use Sexodome\SexodomeApi\Application\CreatePopunderCommandHandler;
use Sexodome\SexodomeApi\Application\CreateTagCommandHandler;
use Sexodome\SexodomeApi\Application\DeleteCategoryCommandHandler;
use Sexodome\SexodomeApi\Application\DeleteCronjobCommandHandler;
use Sexodome\SexodomeApi\Application\DeletePopunderCommandHandler;
use Sexodome\SexodomeApi\Application\DeleteSceneCommandHandler;
use Sexodome\SexodomeApi\Application\DeleteSiteCommandHandler;
use Sexodome\SexodomeApi\Application\DeleteTagCommandHandler;
use Sexodome\SexodomeApi\Application\GetSiteScenesCommandHandler;
use Sexodome\SexodomeApi\Application\GetSiteCommandHandler;
use Sexodome\SexodomeApi\Application\GetSitesCommandHandler;
use Sexodome\SexodomeApi\Application\ImportScenesCommandHandler;
use Sexodome\SexodomeApi\Application\UpdateCategoryTagsCommandHandler;
use Sexodome\SexodomeApi\Application\UpdateCategoryTranslationCommandHandler;
use Sexodome\SexodomeApi\Application\UpdateOrderCategoriesCommandHandler;
use Sexodome\SexodomeApi\Application\SaveSiteColorsCommandHandler;
use Sexodome\SexodomeApi\Application\UpdateSceneTranslationCommandHandler;
use Sexodome\SexodomeApi\Application\UpdateSiteConfigCommandHandler;
use Sexodome\SexodomeApi\Application\UpdateSiteGoogleUACommandHandler;
use Sexodome\SexodomeApi\Application\UpdateSiteIframeCommandHandler;
use Sexodome\SexodomeApi\Application\ShowCategoryTagsCommandHandler;
use Sexodome\SexodomeApi\Application\ShowCategoryThumbsCommandHandler;
use Sexodome\SexodomeApi\Application\ShowOrderCategoriesCommandHandler;
use Sexodome\SexodomeApi\Application\ShowScenePreviewCommandHandler;
use Sexodome\SexodomeApi\Application\ShowSceneThumbsCommandHandler;
use Sexodome\SexodomeApi\Application\ShowSiteCategoriesCommandHandler;
use Sexodome\SexodomeApi\Application\ShowSiteCronjobsCommandHandler;
use Sexodome\SexodomeApi\Application\ShowSitePornstarsCommandHandler;
use Sexodome\SexodomeApi\Application\ShowSiteTagsCommandHandler;
use Sexodome\SexodomeApi\Application\UnverifiedUserCommandHandler;
use Sexodome\SexodomeApi\Application\UpdateTagTranslationCommandHandler;
use Sexodome\SexodomeApi\Application\UploadCategoryThumbnailCommandHandler;
use Sexodome\SexodomeApi\Application\UploadSiteLogoCommandHandler;
use Sexodome\SexodomeApi\Application\CreateCronjobCommandHandler;
use Sexodome\SexodomeApi\Application\CreateSiteCommandHandler;
use Illuminate\Support\Facades\Request;
use App\Model\Language;
use App\Model\Site;
use App\Model\Category;
use App\Model\Tag;
use App\rZeBot\sexodomeKernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('CheckVerifyUser');
        $this->middleware('auth');
    }

    public function unverified()
    {
    }

    public function welcome()
    {
        return view('panel.welcome');
    }

    public function home()
    {
        return redirect()->route('sites');
    }

    public function sites()
    {
        return view('panel.sites', (new GetSitesCommandHandler())->execute(Auth::user()->id));
    }

    public function site($site_id)
    {
        return view('panel.site', (new GetSiteCommandHandler())->execute($site_id));
    }

    public function scenes($site_id)
    {
        return view('panel.scenes', (new GetSiteScenesCommandHandler())->execute(
            $site_id,
            App::make('sexodomeKernel')->perPageScenes,
            $searchParameters = [
                'query'             => Request::input('q', false),
                'tag_query'         => Request::input('tag_q'),
                'duration'          => Request::input('duration'),
                'category_query'    => Request::input('category_string'),
                'empty_title'       => Request::input('empty_title') == "on",
                'empty_description' => Request::input('empty_description') == "on",
            ]
        ));
    }

    public function saveTagTranslation($tag_id)
    {
        $tag = Tag::findOrFail($tag_id);

        return json_encode((new UpdateTagTranslationCommandHandler())->execute(
            $tag_id,
            $tag->site->language->id,
            Request::input('language_' . $tag->site->language->id),
            Request::input('status', false)
        ));
    }

    public function saveCategoryTranslation($category_id)
    {
        $category = Category::findOrFail($category_id);

        return json_encode((new UpdateCategoryTranslationCommandHandler())->execute(
            $category_id,
            $category->site->language_id,
            Request::input('language_' . $category->site->language->id),
            Request::input('thumbnail'),
            $status = true
        ));
    }

    public function saveSceneTranslation($scene_id)
    {
        return json_encode((new UpdateSceneTranslationCommandHandler())->execute(
            $scene_id,
            Request::input('title'),
            Request::input('description'),
            Request::input('selectedThumb', null)
        ));
    }

    public function scenePreview($scene_id)
    {
        return view('panel.ajax._ajax_preview', (new ShowScenePreviewCommandHandler())->execute($scene_id));
    }

    public function fetch($site_id)
    {
        return json_encode((new ImportScenesCommandHandler())->execute(
            $site_id,
            Request::input('feed_name'),
            $parameters = [
                'max'                 => Request::input('max'),
                'duration'            => Request::input('duration'),
                'tags'                => Request::input('tags'),
                'only_with_pornstars' => Request::input('only_with_pornstars') == 1 ? true : false
            ]
        ));
    }

    public function sceneThumbs($scene_id)
    {
        return view('panel.ajax._ajax_scene_thumbs', (new ShowSceneThumbsCommandHandler())->execute($scene_id));
    }

    public function addSite()
    {
        if (Request::isMethod('post')) {
            return view('panel.add_site', (new CreateSiteCommandHandler())->execute( Request::input('domain') ));
        }

        return view('panel.add_site', [ 'sites' => Site::where('user_id', '=', Auth::user()->id)->get() ]);
    }

    public function checkDomain()
    {
        return json_encode((new CheckDomainCommandHandler())->execute( Request::input('domain') ));
    }

    public function deleteCronJob($cronjob_id)
    {
        return json_encode((new DeleteCronjobCommandHandler())->execute( $cronjob_id ));
    }

    public function deleteSite($site_id)
    {
        (new DeleteSiteCommandHandler())->execute( $site_id );

        return redirect()->route('sites', []);
    }

    public function categoryThumbs($category_id)
    {
        return view('panel.ajax._ajax_category_thumbs', (new ShowCategoryThumbsCommandHandler())->execute(
            $category_id,
            App::make('sexodomeKernel')->sex_types
        ));
    }

    public function categoryUnlock($category_translation_id)
    {
        return json_encode((new CategoryUnlockCommandHandler())->execute( $category_translation_id ));
    }

    public function orderCategories($site_id)
    {
        if (Request::input('o') != "") {
            return json_encode((new UpdateOrderCategoriesCommandHandler())->execute( $site_id, Request::input('o') ));
        }

        return view('panel.categories_order', (new ShowOrderCategoriesCommandHandler())->execute( $site_id, 100 ));
    }

    public function categoryTags($category_id)
    {
        if ( Request::isMethod('post') ) {
            return json_encode((new UpdateCategoryTagsCommandHandler())->execute( $category_id, Request::input('categories') ));
        }

        return view('panel.ajax._ajax_category_tags', (new ShowCategoryTagsCommandHandler())->execute( $category_id ));
    }

    public function createCategory($site_id)
    {
        if (Request::isMethod('post')) {
            return json_encode((new CreateCategoryCommandHandler())->execute(
                $site_id,
                Request::input('language_en'),
                $this->prepareCategoryRequestData($site_id)
            ));
        } else {
            return view('panel.ajax._ajax_site_create_category', [ 'site' => Site::findOrFail($site_id) ] );
        }
    }

    public function createTag($site_id)
    {
        if (Request::isMethod('post')) {
            return json_encode((new CreateTagCommandHandler())->execute( $site_id, $this->prepareTagRequestData($site_id) ));
        } else {
            return view('panel.ajax._ajax_site_create_tag', [ 'site' => Site::findOrFail($site_id) ]);
        }
    }

    public function updateSiteSEO($site_id)
    {
        return json_encode((new UpdateSiteConfigCommandHandler())->execute( $site_id, $configData = [
            'status' => Request::input('status'),
            'language_id' => Request::input('language_id'),
            'category_url' => Request::input('category_url'),
            'pornstars_url' => Request::input('pornstars_url'),
            'pornstar_url' => Request::input('pornstar_url'),
            'video_url' => Request::input('video_url'),
            'contact_email' => Request::input('contact_email'),
            'type_id' => Request::input('type_id'),
            'logo_h1' => Request::input('logo_h1'),
            'categories_h3' => Request::input('categories_h3'),
            'h2_home' => Request::input('h2_home'),
            'h2_category' => Request::input('h2_category'),
            'h2_pornstars' => Request::input('h2_pornstars'),
            'h2_pornstar' => Request::input('h2_pornstar'),
            'title_index' => Request::input('title_index'),
            'title_category' => Request::input('title_category'),
            'description_index' => Request::input('description_index'),
            'description_category' => Request::input('description_category'),
            'title_pornstars' => Request::input('title_pornstars'),
            'title_pornstar' => Request::input('title_pornstar'),
            'description_pornstars' => Request::input('description_pornstars'),
            'description_pornstar' => Request::input('description_pornstar'),
            'title_tag' => Request::input('title_tag'),
            'description_tag' => Request::input('description_tag'),
            'title_topscenes' => Request::input('title_topscenes'),
            'description_topscenes' => Request::input('description_topscenes'),
            'domain' => Request::input('domain'),
            'header_text' => Request::input('header_text', ""),
            'link_billboard' => Request::input('link_billboard'),
            'link_billboard_mobile' => Request::input('link_billboard_mobile'),
            'google_analytics' => Request::input('google_analytics'),
            'javascript' => Request::input('javascript')
        ] ));
    }

    public function updateGoogleData($site_id, Request $request)
    {
        return json_encode((new UpdateSiteGoogleUACommandHandler())->execute( $site_id, Request::input('ga_view_' . $site_id) ));
    }

    public function updateIframeData($site_id)
    {
        return json_encode((new UpdateSiteIframeCommandHandler())->execute(
            $site_id,
            (Request::input('iframe_site_id_' . $site_id) != "") ? Request::input('iframe_site_id_' . $site_id) : null
        ));
    }

    public function updateLogo($site_id)
    {
        return json_encode((new UploadSiteLogoCommandHandler())->execute(
            $site_id,
            sexodomeKernel::getLogosFolder(),
            sexodomeKernel::getFaviconsFolder(),
            sexodomeKernel::getHeadersFolder()
        ));
    }

    public function updateColors($site_id)
    {
        return json_encode((new SaveSiteColorsCommandHandler())->execute( $site_id, [
            'color'   => Request::input('color') != "" ? Request::input('color') : null,
            'color2'  => Request::input('color2') != "" ? Request::input('color2') : null,
            'color3'  => Request::input('color3') != "" ? Request::input('color3') : null,
            'color4'  => Request::input('color4') != "" ? Request::input('color4') : null,
            'color5'  => Request::input('color5') != "" ? Request::input('color5') : null,
            'color6'  => Request::input('color6') != "" ? Request::input('color6') : null,
            'color7'  => Request::input('color7') != "" ? Request::input('color7') : null,
            'color8'  => Request::input('color8') != "" ? Request::input('color8') : null,
            'color9'  => Request::input('color9') != "" ? Request::input('color9') : null,
            'color10' => Request::input('color10') != "" ? Request::input('color10') : null,
            'color11' => Request::input('color11') != "" ? Request::input('color11') : null,
            'color12' => Request::input('color12') != "" ? Request::input('color12') : null,
        ]));
   }

    public function uploadCategoryThumbnail($category_id)
    {
        return json_encode((new UploadCategoryThumbnailCommandHandler())->execute($category_id ));
    }

    public function ajaxDeleteCategory($category_id)
    {
        return json_encode((new DeleteCategoryCommandHandler())->execute($category_id ));
    }

    public function ajaxDeleteTag($tag_id)
    {
        return json_encode((new DeleteTagCommandHandler())->execute( $tag_id ));
    }

    public function ajaxDeleteScene($scene_id)
    {
        return json_encode((new DeleteSceneCommandHandler())->execute( $scene_id ));
    }

    public function ajaxPopunders($site_id)
    {
        return view('panel.ajax._ajax_site_popunders', ['popunders' => Site::findOrFail($site_id)->popunders()->get()]);
    }

    public function ajaxSavePopunder($site_id, Request $request)
    {
        return json_encode((new CreatePopunderCommandHandler())->execute( $site_id, Request::input('url', false)) );
    }

    public function ajaxDeletePopunder($popunder_id)
    {
        return json_encode((new DeletePopunderCommandHandler())->execute( $popunder_id));
    }

    public function ajaxSaveCronJob()
    {
        return json_encode((new CreateCronjobCommandHandler())->execute(
            Request::input('feed_name'),
            Request::input('site_id'),
            $parameters = [
                'max'                 => Request::input('max'),
                'duration'            => Request::input('duration'),
                'tags'                => Request::input('duration'),
                'only_with_pornstars' => Request::input('only_with_pornstars') == 1 ? true : false
            ]
        ));
    }

    public function ajaxCronJobs($site_id)
    {
        return view('panel.ajax._ajax_site_cronjobs', (new ShowSiteCronjobsCommandHandler())->execute( $site_id ));
    }

    public function ajaxSiteTags($site_id, Request $request)
    {
        return view('panel.ajax._ajax_site_tags', (new ShowSiteTagsCommandHandler())->execute(
            $site_id,
            Request::input('q'),
            App::make('sexodomeKernel')->perPageTags
        ));
    }

    public function ajaxSiteCategories($site_id)
    {
        return view('panel.ajax._ajax_site_categories', (new ShowSiteCategoriesCommandHandler())->execute(
            $site_id,
            Request::input('q'),
            App::make('sexodomeKernel')->perPagePanelCategories,
            $order = Request::input('order_by_nscenes', false)
        ));
    }

    public function ajaxSitePornstars($site_id)
    {
        return view('panel.ajax._ajax_site_pornstars', (new ShowSitePornstarsCommandHandler())->execute(
            $site_id,
            App::make('sexodomeKernel')->perPagePanelPornstars
        ));
    }

    protected function prepareCategoryRequestData($site_id)
    {
        $languagesData  = [];
        foreach(Language::getAddLanguages(Site::findOrFail($site_id)->language_id) as $language) {
            $languagesData[$language->code] = Request::input('language_'.$language->code);
        }

        return $languagesData;
    }

    protected function prepareTagRequestData($site_id)
    {
        $languagesData  = [];
        foreach(Language::getAddLanguages(Site::findOrFail($site_id)->language_id) as $language) {
            $languagesData[$language->code] = Request::input('language_'.$language->code);
        }

        return $languagesData;
    }
}
