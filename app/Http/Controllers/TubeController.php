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
use App\Model\SceneClick;
use App\Model\CategoryTranslation;
use App\Model\Category;

class TubeController extends Controller
{
    var $commons;

    public function __construct()
    {
        $this->commons = new rZeBotCommons();
    }

    public function index($host)
    {
        $query_string = Request::get('q');

        // scenes
        $scenes = Scene::getAllTranslated($this->commons->language->id)
            ->where('status', 1)
            ->where('site_id', $this->commons->site->id)
            ->orderBy('published_at', 'desc')
            ->paginate($this->commons->perPage)
        ;

        // seo
        $seo_title = str_replace("{domain}", $this->commons->site->domain, $this->commons->site->title_index);
        $seo_description = str_replace("{domain}", $this->commons->site->domain, $this->commons->site->description_index);

        return response()->view('tube.index', [
            'profile'         => $host,
            'scenes'          => $scenes,
            'categories'      => $this->commons->site->categories()->get(),
            'query_string'    => $query_string,
            'resultsPerPage'  => $this->commons->perPage,
            'language'        => $this->commons->language,
            'languages'       => $this->commons->languages,
            'site'            => $this->commons->site,
            'seo_title'       => $seo_title,
            'seo_description' => $seo_description
        ]);
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
        $seo_title = str_replace("{domain}", $this->commons->language->domain, $this->commons->language->title_index);
        $seo_description = str_replace("{domain}", $this->commons->language->domain, $this->commons->language->description_index);

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
        $seo_title = str_replace("{tag}", $tagTranslation->name, $this->site->title_tag);
        $seo_title = str_replace("{domain}", $this->site->domain, $seo_title);

        $seo_description = str_replace("{tag}", $tagTranslation->name, $this->site->description_tag);
        $seo_description = str_replace("{domain}", $this->site->domain, $seo_description);

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
        // check if tag exists, else redirect to route
        if (CategoryTranslation::where('permalink', $permalinkCategory)->count() == 0) {
            return redirect()->route('index');
        }

        $categoryTranslation = CategoryTranslation::where('permalink', $permalinkCategory)
            ->where('language_id', $this->commons->site->language_id)
            ->first()
        ;

        if (!$categoryTranslation) {
            abort(404, "Category not found");
        }

        $category = $categoryTranslation->category;

        // get scenes
        $scenes = Scene::getTranslationsForCategory($permalinkCategory, $this->commons->language->id)
            ->where('scenes.site_id', $this->commons->site->id)
            ->paginate($this->commons->perPagez)
        ;

        // seo
        $seo_title = str_replace("{category}", $categoryTranslation->name, $this->commons->site->title_category);
        $seo_title = str_replace("{domain}", $this->commons->site->domain, $seo_title);

        $seo_description = str_replace("{category}", $categoryTranslation->name, $this->commons->site->description_category);
        $seo_description = str_replace("{domain}", $this->commons->site->domain, $seo_description);

        return response()->view('tube.search', [
            'profile'         => $profile,
            'scenes'          => $scenes,
            'categories'      => $this->commons->site->categories()->get(),
            'resultsPerPage'  => $this->commons->perPage,
            'query_string'    => $category->name,
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

        // video log
        $sceneClick = new SceneClick();
        $sceneClick->scene_id = $scene->id;
        $sceneClick->referer = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:"";
        $sceneClick->save();

        if ($scene->tags()->count() > 0) {
            $randomTag = $scene->tags()->orderByRaw("RAND()")->first();
            $tag = $randomTag->translations()->where('language_id', $this->commons->language->id)->first();
            $related = Scene::getTranslationsForTag($tag->name, $this->commons->language->id);
        } else {
            $related = Scene::getAllTranslated($this->commons->language->id);
        }

        // seo
        $seo_title = str_replace("{domain}", $this->commons->language->domain, $scene->title);
        $seo_description = str_replace("{domain}", $this->commons->language->domain, $scene->description);

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
        $seo_title = str_replace("{domain}", $this->commons->language->domain, $this->commons->language->index_topscenes);
        $seo_description = str_replace("{domain}", $this->commons->language->domain, $this->commons->language->description_topscenes);

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
        $seo_title = str_replace("{domain}", $this->commons->language->domain, $this->commons->language->title);
        $seo_description = str_replace("{domain}", $this->commons->language->domain, $this->commons->language->description);

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
}
