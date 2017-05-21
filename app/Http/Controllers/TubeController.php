<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\rZeBot\sexodomeKernel;
use App\Model\Category;
use App\Model\Scene;
use App\Model\Pornstar;
use Illuminate\Support\Facades\Storage;

class TubeController extends Controller
{
    var $sexodomeKernel;

    public function __construct()
    {
        $this->sexodomeKernel = new sexodomeKernel();
    }

    public function categories($profile, $page = 1, Request $request)
    {
        $categories = Category::getTranslationByStatus(1, $this->sexodomeKernel->language->id)
            ->where('site_id', '=', $this->sexodomeKernel->site->id)
            ->orderBy('categories.cache_order', 'DESC')
            ->orderBy('categories.nscenes', 'DESC')
            ->paginate($this->sexodomeKernel->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.categories', [
            'categories'     => $categories,
            'sexodomeKernel' => $this->sexodomeKernel,
            'page'           => $page,
        ]);
    }

    public function search($profile, Request $request)
    {
        $scenes = Scene::getTranslationSearch( $request->input('q', false), $this->sexodomeKernel->language->id)
            ->where('site_id', $this->sexodomeKernel->site->id)
            ->where('status', 1)
            ->orderBy('scene_id', 'desc')
            ->paginate($this->sexodomeKernel->perPage)
        ;

        return response()->view('tube.search', [
            'scenes'         => $scenes,
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
    }

    public function category($profile, $permalinkCategory, $page = 1, Request $request)
    {
        $categoryTranslation = Category::getTranslationFromPermalink(
            $permalinkCategory,
            $this->sexodomeKernel->getSite()->id,
            $this->sexodomeKernel->getLanguage()->id
        );

        if (!$categoryTranslation) {
            abort(404, 'Category not found');
        }

        // get scenes
        $scenes = Scene::getTranslationsForCategory(
                $categoryTranslation->category->id,
                $this->sexodomeKernel->language->id,
                $request->input('order', false)
            )
            ->paginate($this->sexodomeKernel->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.category', [
            'scenes'              => $scenes,
            'categoryTranslation' => $categoryTranslation,
            'permalinkCategory'   => $permalinkCategory,
            'sexodomeKernel'      => $this->sexodomeKernel
        ]);
    }

    public function pornstars($profile, $page = 1, Request $request)
    {
        $pornstars = Pornstar::where('site_id', $this->sexodomeKernel->site->id)
            ->paginate($this->sexodomeKernel->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.pornstars', [
            'pornstars'      => $pornstars,
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
    }

    public function pornstar($profile, $permalinkPornstar, $page = 1, Request $request)
    {
        $pornstar = Pornstar::where('pornstars.site_id', '=', $this->sexodomeKernel->site->id)
            ->where('permalink', $permalinkPornstar)
            ->first()
        ;

        if (!$pornstar) {
            abort(404, "Pornstar not found");
        }

        $scenes = Scene::getTranslationsForPornstar($pornstar->id, $this->sexodomeKernel->language->id)
            ->paginate($this->sexodomeKernel->perPageScenes, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.pornstar', [
            'scenes'         => $scenes,
            'pornstar'       => $pornstar,
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
    }

    public function video($profile, $permalink, Request $request)
    {
        $scene = Scene::getTranslationByPermalink($permalink, $this->sexodomeKernel->language->id, $this->sexodomeKernel->site->id);

        if (!$scene) {
            abort(404, 'Scene not found');
        }

        Scene::addSceneClick($scene);

        if ($scene->tags()->count() > 0) {
            $randomTag = $scene->tags()->orderByRaw("RAND()")->first();
            $tag = $randomTag->translations()->where('language_id', $this->sexodomeKernel->language->id)->first();
            $related = Scene::getTranslationsForTag($tag->name, $this->sexodomeKernel->language->id);
            if (count($related) == 0) {
                $related = Scene::getAllTranslated($this->sexodomeKernel->language->id);
            }
        } else {
            $related = Scene::getAllTranslated($this->sexodomeKernel->language->id);
        }

        return response()->view('tube.video', [
            'video'          => $scene,
            'related'        => $related->orderBy('rate', 'desc')->limit(4)->get(),
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
    }

    public function iframe($profile, $scene_id, Request $request)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort(404, 'Scene not found');
        }
        return view('tube.iframe', [
            'scene'          => $scene,
            'sexodomeKernel' => $this->sexodomeKernel,
        ]);
    }

    public function ads($profile, Request $request)
    {
        $categories = $this->sexodomeKernel->site->categories()->where('status', 1)->limit(18)->get();

        return response()->view('tube.ads', [
            'categories'     => $categories,
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
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

    public function sitemap(Request $request)
    {
        if ($this->sexodomeKernel->site) {
            $sitemapFile = $this->sexodomeKernel->site->getSitemap();
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

    public function dmca($profile, Request $request)
    {
        return response()->view('tube.static.dmca', [
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
    }

    public function terms($profile, Request $request)
    {
        return response()->view('tube.static.terms', [
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
    }

    public function C2257($profile, Request $request)
    {
        return response()->view('tube.static.2257', [
            'sexodomeKernel' => $this->sexodomeKernel
        ]);
    }

}
