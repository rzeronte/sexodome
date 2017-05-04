<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\rZeBot\rZeBotCommons;
use App\Model\Category;
use App\Model\Scene;
use App\Model\CategoryTranslation;
use App\Model\Pornstar;
use Illuminate\Support\Facades\Storage;

class TubeController extends Controller
{
    var $commons;

    public function __construct()
    {
        $this->commons = new rZeBotCommons();
    }

    public function categories($profile, $page = 1, Request $request)
    {
        $categories = Category::getTranslationByStatus(1, $this->commons->language->id)
            ->where('site_id', '=', $this->commons->site->id)
            ->orderBy('categories.cache_order', 'DESC')
            ->orderBy('categories.nscenes', 'DESC')
            ->paginate($this->commons->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.categories', [
            'profile'         => $profile,
            'categories'      => $categories,
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => $request->input('q'),
            'language'        => $this->commons->language,
            'seo_title'       => $this->commons->site->getCategoriesTitle($page),
            'seo_description' => $this->commons->site->getCategoriesDescription(),
            'site'            => $this->commons->site,
            'total_scenes'    => $this->commons->site->getTotalScenes(),
            'page'            => $page,
        ])->header('Cache-control', 'max-age=3600');;
    }

    public function search($profile, Request $request)
    {
        $query_string = $request->input('q', false);

        $scenes = Scene::getTranslationSearch($query_string, $this->commons->language->id)
            ->where('site_id', $this->commons->site->id)
            ->where('status', 1)
            ->orderBy('scene_id', 'desc')
            ->paginate($this->commons->perPage)
        ;

        return response()->view('tube.search', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'query_string'    => $query_string,
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $this->commons->site->getCategoriesTitle(),
            'seo_description' => $this->commons->site->getCategoriesDescription(),
            'site'            => $this->commons->site,

        ])->header('Cache-control', 'max-age=3600');
    }

    public function category($profile, $permalinkCategory, $page = 1, Request $request)
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
                $request->input('order', false)
            )
            ->paginate($this->commons->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.category', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'categoryTranslation' => $categoryTranslation,
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => '',
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $this->commons->site->getCategoryTitle($categoryTranslation->name, $page),
            'seo_description' => $this->commons->site->getCategoryDescription($categoryTranslation->name),
            'site'            => $this->commons->site,
            'permalinkCategory'=> $permalinkCategory,
        ])->header('Cache-control', 'max-age=3600');
    }

    public function pornstars($profile, $page = 1, Request $request)
    {

        $pornstars = Pornstar::where('site_id', $this->commons->site->id)
            ->paginate($this->commons->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.pornstars', [
            'query_string'    => "",
            'profile'         => $profile,
            'pornstars'       => $pornstars,
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $this->commons->site->getPornstarsTitle($page),
            'seo_description' => $this->commons->site->getPornstarsDescription(),
            'site'            => $this->commons->site,
        ])->header('Cache-control', 'max-age=3600');
    }

    public function pornstar($profile, $permalinkPornstar, $page = 1, Request $request)
    {
        $pornstar = App\Model\Pornstar::where('pornstars.site_id', '=', $this->commons->site->id)
            ->where('permalink', $permalinkPornstar)
            ->first()
        ;

        if (!$pornstar) {
            abort(404, "Pornstar not found");
        }

        $scenes = Scene::getTranslationsForPornstar($pornstar->id, $this->commons->language->id)
            ->paginate($this->commons->perPageScenes, $columns = ['*'], $pageName = 'page', $page)
        ;
        return response()->view('tube.pornstar', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'categories'      => $this->commons->site->categories()->get(),
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => '',
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $this->commons->site->getPornstarTitle($pornstar->name),
            'seo_description' => $this->commons->site->getPornstarDescription($pornstar->name),
            'site'            => $this->commons->site,
            'pornstar'        => $pornstar
        ])->header('Cache-control', 'max-age=3600');
    }

    public function video($profile, $permalink, Request $request)
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

        return response()->view('tube.video', [
            'profile'         => $profile,
            'video'           => $scene,
            'related'         => $related->orderBy('rate', 'desc')->limit(4)->get(),
            'query_string'    => "",
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'seo_title'       => $this->commons->site->getSceneTitle($scene),
            'seo_description' => $this->commons->site->getSceneDescription($scene),
            'site'            => $this->commons->site,
        ])->header('Cache-control', 'max-age=3600');
    }

    public function iframe($profile, $scene_id, Request $request)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort(404, 'Scene not found');
        }

        return view('tube.iframe', [
            'site'     => $this->commons->site,
            'profile'  => $profile,
            'language' => $this->commons->language,
            'scene'    => $scene
        ]);
    }

    public function ads($profile, Request $request)
    {
        $categories = $this->commons->site->categories()->where('status', 1)->limit(18)->get();

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

    public function dmca($profile, Request $request)
    {
        return response()->view('tube.static.dmca', [
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

    public function terms($profile, Request $request)
    {
        return response()->view('tube.static.terms', [
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

    public function C2257($profile, Request $request)
    {
        return response()->view('tube.static.2257', [
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

        Scene::addSceneClick($scene, $ua = $request->header('User-Agent'));

        return redirect($scene->url);
    }

    public function sitemap(Request $request) {
        if ($this->commons->site) {
            $sitemapFile = $this->commons->site->getSitemap();
            $file = Storage::disk('web')->get($sitemapFile);
        } else {
            $file = Storage::disk('web')->get('sexodome-sitemap.xml');
        }

        return response($file, "200")->header('Content-Type', "application/xml");
    }

    public function siteError($profile, Request $request)
    {
        abort(503, 'Error');

    }
}
