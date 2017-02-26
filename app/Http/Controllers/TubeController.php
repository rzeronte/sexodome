<?php

namespace App\Http\Controllers;

use App;
use DB;
use Request;
use Validator;
use Input;
use Session;
use URL;
use Auth;
use App\rZeBot\rZeBotCommons;
use App\Model\Scene;
use App\Model\Site;
use App\Model\CategoryTranslation;
use App\Model\Category;
use App\Model\TagTranslation;
use Storage;

class TubeController extends Controller
{
    var $commons;

    public function __construct()
    {
        $this->commons = new rZeBotCommons();
    }

    public function categories($profile)
    {

        $query_string = Request::get('q');

        $categories = Category::getTranslationByStatus(1, $this->commons->language->id)
            ->where('site_id', '=', $this->commons->site->id)
            ->orderBy('categories.cache_order', 'DESC')
            ->orderBy('categories.nscenes', 'DESC')
            ->paginate($this->commons->perPageCategories)
        ;

        $categoriesAlphabetical = Category::getTranslationByStatus(1, $this->commons->language->id)
            ->where('site_id', '=', $this->commons->site->id)
            ->orderBy('categories_translations.name', 'ASC')
        ;

        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->title_index);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->description_index);

        return response()->view('tube.categories', [
            'profile'         => $profile,
            'categories'      => $categories,
            'categoriesAlphabetical' => $categoriesAlphabetical->get(),
            'categories_head' => $this->commons->site->categories()->get(),
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => $query_string,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description,
            'site'            => $this->commons->site,
            'total_scenes'    => $this->commons->site->getTotalScenes(),
        ])->header('Cache-control', 'max-age=3600');
    }

    public function search($profile)
    {
        $query_string = Request::get('q', false);

        $scenes = Scene::getTranslationSearch($query_string, $this->commons->language->id)
            ->where('site_id', $this->commons->site->id)
            ->where('status', 1)
            ->orderBy('scene_id', 'desc')
            ->paginate($this->commons->perPage)
        ;

        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->title_index);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->description_index);

        return response()->view('tube.search', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'categories'      => $this->commons->site->categories()->get(),
            'query_string'    => $query_string,
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description,
            'site'            => $this->commons->site,

        ])->header('Cache-control', 'max-age=3600');
    }

    public function tag($profile, $permalinkTag)
    {
        // check if tag exists, else redirect to route
        if (TagTranslation::where('permalink', $permalinkTag)->count() == 0) {
            return redirect()->route('categories', ['domain' => $this->commons->site->getHost()]);
        }

        $tagTranslation = TagTranslation::where('permalink', $permalinkTag)->first();
        $tag = $tagTranslation->tag;

        // get scenes
        $scenes = Scene::getTranslationsForTag($permalinkTag, $this->commons->language->id)
            ->where('scenes.site_id', $this->commons->site->id)
            ->paginate($this->commons->perPage)
        ;

        // seo
        $seo_title = str_replace("{tag}", $tagTranslation->name, $this->commons->site->title_tag);
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $seo_title);

        $seo_description = str_replace("{tag}", $tagTranslation->name, $this->commons->site->description_tag);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $seo_description);

        return response()->view('tube.search', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'categories'      => $this->commons->site->categories()->get(),
            'tagTranslation'  => $tagTranslation,
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => $tag->name,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description,
            'site'            => $this->commons->site,

        ])->header('Cache-control', 'max-age=3600');
    }

    public function category($profile, $permalinkCategory)
    {
        if (CategoryTranslation::join('categories','categories.id', '=', 'categories_translations.category_id')
                ->where('categories.site_id', '=', $this->commons->site->id)
                ->where('permalink', $permalinkCategory)
                ->where('language_id', $this->commons->site->language_id)
                ->count() == 0
        ) {
            abort(404, 'Scene not found');
        }

        $categoryTranslation = CategoryTranslation::join('categories','categories.id', '=', 'categories_translations.category_id')
            ->where('permalink', $permalinkCategory)
            ->where('categories.site_id', '=', $this->commons->site->id)
            ->where('language_id', $this->commons->site->language_id)
            ->first()
        ;

        // get scenes
        $scenes = Scene::getTranslationsForCategory(
            $categoryTranslation->category->id,
            $this->commons->language->id,
            Input::get('order', false)
        )
        ->paginate($this->commons->perPageCategories);

        // seo
        $seo_title = str_replace("{category}", $categoryTranslation->name, $this->commons->site->title_category);
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $seo_title);

        $seo_description = str_replace("{category}", $categoryTranslation->name, $this->commons->site->description_category);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $seo_description);

        return response()->view('tube.search', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'categories'      => $this->commons->site->categories()->get(),
            'categoryTranslation' => $categoryTranslation,
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => '',
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description,
            'site'            => $this->commons->site,
            'permalinkCategory'=> $permalinkCategory,
        ])->header('Cache-control', 'max-age=3600');
    }

    public function video($profile, $permalink)
    {
        $scene = Scene::getTranslationByPermalink($permalink, $this->commons->language->id, $this->commons->site->id);
        if (!$scene) {
            abort(404, 'Scene not found');
        }

        Scene::addSceneClick($scene);

        if ($scene->tags()->count() > 0) {
            $randomTag = $scene->tags()->orderByRaw("RAND()")->first();
            $tag = $randomTag->translations()->where('language_id', $this->commons->language->id)->first();
            $related = Scene::getTranslationsForTag($tag->name, $this->commons->language->id);
            if (count($related) == 0) {
                $related = Scene::getAllTranslated($this->commons->language->id);
            }
        } else {
            $related = Scene::getAllTranslated($this->commons->language->id);
        }

        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $scene->title);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $scene->description);


        // Si no hay descripción hacemos un montaje: title + categorías + host
        if (strlen(trim($seo_description)) == 0) {
            $array_categories = [];
            foreach ($scene->categories()->get() as $category) {
                $translation = $category->translations()->where('language_id', $this->commons->site->language_id)->first();
                $array_categories[] = $translation->name;
            }

            $seo_description = $seo_title . " " . implode("-", $array_categories) . " " . $this->commons->site->getHost();
        }

        return response()->view('tube.video', [
            'profile'         => $profile,
            'video'           => $scene,
            'related'         => $related->orderBy('rate', 'desc')->limit(4)->get(),
            'categories'      => $this->commons->site->categories()->get(),
            'query_string'    => "",
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description,
            'site'            => $this->commons->site,
        ])->header('Cache-control', 'max-age=3600');
    }

    public function iframe($profile, $scene_id)
    {
        $scene = Scene::find($scene_id);

        return view('tube.iframe', [
            'site'     => $this->commons->site,
            'profile'  => $profile,
            'language' => $this->commons->language,
            'scene'    => $scene
        ]);
    }

    public function ads($profile)
    {
        $categories = $this->commons->site->categories()->limit(18)->get();

        return response()->view('tube.ads', [
            'profile'        => $profile,
            'resultsPerPage' => $this->commons->perPage,
            'language'       => $this->commons->language,
            'languages'      => $this->commons->languages,
            'title'          => "Iframe - Ads",
            'categories'     => $categories,
            'site'           => $this->commons->site,
        ])->header('Cache-control', 'max-age=3600');
    }

    public function dmca($profile)
    {
        return response()->view('tube.dmca', [
            'profile'         => $profile,
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'query_string'    => "",
            'seo_title'       => "DMCA",
            'seo_description' => "",
            'site'            => $this->commons->site,

        ])->header('Cache-control', 'max-age=3600');
    }

    public function terms($profile)
    {
        return response()->view('tube.terms', [
            'profile'         => $profile,
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'query_string'    => "",
            'seo_title'       => "Terms of use",
            'seo_description' => "",
            'site'            => $this->commons->site,

        ])->header('Cache-control', 'max-age=3600');
    }

    public function C2257($profile)
    {
        return response()->view('tube.2257', [
            'profile'         => $profile,
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'query_string'    => "",
            'seo_title'       => "2257",
            'seo_description' => "",
            'site'            => $this->commons->site,

        ])->header('Cache-control', 'max-age=3600');
    }

    public function out($profile, $scene_id, Request $request)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort(404, "Scene not found");
        }

        Scene::addSceneClick($scene, $ua = $request::header('User-Agent'));

        return redirect($scene->url);
    }

    public function sitemap() {
        if ($this->commons->site) {
            $sitemapFile = $this->commons->site->getSitemap();
            $file = Storage::disk('web')->get($sitemapFile);
        } else {
            $file = Storage::disk('web')->get('sexodome-sitemap.xml');
        }

        return response($file, "200")->header('Content-Type', "application/xml");
    }

    public function pornstars($profile)
    {
        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->title_pornstars);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->description_pornstars);

        return response()->view('tube.pornstars', [
            'query_string'    => "",
            'profile'         => $profile,
            'pornstars'      => App\Model\Pornstar::where('site_id', $this->commons->site->id)->paginate($this->commons->perPageCategories),
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description,
            'site'            => $this->commons->site,
        ])->header('Cache-control', 'max-age=3600');
    }

    public function pornstar($profile, $permalinkPornstar)
    {
        $pornstar = App\Model\Pornstar::where('pornstars.site_id', '=', $this->commons->site->id)
            ->where('permalink', $permalinkPornstar)
            ->first()
        ;

        if (!$pornstar) {
            abort(404, "Pornstar not found");
        }

        $scenes = Scene::getTranslationsForPornstar($pornstar->id, $this->commons->language->id)
            ->paginate($this->commons->perPageCategories)
        ;

        // seo
        $seo_title = str_replace("{pornstar}", $pornstar->name, $this->commons->site->title_pornstar);
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $seo_title);

        $seo_description = str_replace("{pornstar}", $pornstar->name, $this->commons->site->description_pornstar);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $seo_description);

        return response()->view('tube.search', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'categories'      => $this->commons->site->categories()->get(),
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => '',
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description,
            'site'            => $this->commons->site,
            'pornstar'        => $pornstar
        ])->header('Cache-control', 'max-age=3600');
    }
}
