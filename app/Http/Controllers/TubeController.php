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
use App\Model\CategoryTranslation;
use App\Model\Category;
use Storage;

class TubeController extends Controller
{
    var $commons;

    public function __construct()
    {
        $this->commons = new rZeBotCommons();
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
            return redirect()->route('index');
        }

        $tagTranslation = TagTranslation::where('permalink', $permalinkTag)->first();
        $tag = $tagTranslation->tag;

        // get scenes
        $scenes = Scene::getTranslationsForTag($permalinkTag, $this->commons->language->id)
            ->where('site_id', $this->commons->site->id)
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
            'categories'      => $this->site->categories()->get(),
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
                ->count() == 0
        ) {
            return redirect()->route('index');
        }

        $categoryTranslation = CategoryTranslation::join('categories','categories.id', '=', 'categories_translations.category_id')
            ->where('permalink', $permalinkCategory)
            ->where('categories.site_id', '=', $this->commons->site->id)
            ->where('language_id', $this->commons->site->language_id)
            ->first()
        ;

        if (!$categoryTranslation) {
            abort(404, "Category not found");
        }

        // get scenes
        $scenes = Scene::getTranslationsForCategory(
            $categoryTranslation->category->id,
            $this->commons->language->id
        )
        ->paginate(1);

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
        } else {
            $related = Scene::getAllTranslated($this->commons->language->id);
        }

        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $scene->title);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $scene->description);

        return response()->view('tube.video', [
            'profile'         => $profile,
            'video'           => $scene,
            'related'         => $related->orderBy('rate', 'desc')->limit(12)->get(),
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

    public function topscenes($profile)
    {
        $query_string = Request::get('q');

        // scenes
        $scenes = Scene::getAllTranslated($this->commons->language->id)
            ->where('status', 1)
            ->where('site_id', $this->commons->site->id)
            ->orderBy('duration', 'desc')
            ->orderBy('rate', 'desc')
            ->paginate(24*3);

        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->index_topscenes);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->description_topscenes);

        return response()->view('tube.index', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'removePaginator' => false,
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

    public function categories($profile)
    {
        $query_string = Request::get('q');

        $categories = Category::getTranslationByStatus(1, $this->commons->language->id)
            ->where('site_id', '=', $this->commons->site->id)
            ->paginate($this->commons->perPageCategories)
        ;

        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->title_index);
        $seo_description = str_replace("{domain}", $this->commons->site->getHost(), $this->commons->site->description_index);

        return response()->view('tube.categories', [
            'profile'         => $profile,
            'categories'      => $categories,
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

    public function ads($profile)
    {
        $scenes = Scene::all()->random(6);

        return response()->view('tube._ads', [
            'profile'        => $profile,
            'resultsPerPage' => $this->commons->perPage,
            'language'       => $this->commons->language,
            'languages'      => $this->commons->languages,
            'title'          => "Iframe - Ads",
            'scenes'         => $scenes,
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

    public function out($profile, $scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort(404, "Scene not found");
        }

        Scene::addSceneClick($scene);

        // el campo 'iframe' es la url, cuando el video pertenece a un feed no embed
        return redirect($scene->iframe);
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
}
