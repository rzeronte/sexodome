<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Model\Category;
use App\Model\Scene;
use App\Model\Pornstar;
use Illuminate\Support\Facades\Storage;

class TubeController extends Controller
{
    public function categories($profile, $page = 1)
    {
        $categories = Category::getForTranslation(
                $status = true,
                App::make('sexodomeKernel')->site->id,
                App::make('sexodomeKernel')->language->id
            )
            ->paginate(App::make('sexodomeKernel')->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.categories', [
            'categories' => $categories,
            'page'       => $page,
        ]);
    }

    public function search($profile, Request $request)
    {
        $scenes = Scene::getTranslationSearch( $request->input('q', false), App::make('sexodomeKernel')->language->id)
            ->where('site_id', App::make('sexodomeKernel')->site->id)
            ->where('status', 1)
            ->orderBy('scene_id', 'desc')
            ->paginate(App::make('sexodomeKernel')->perPage)
        ;

        return response()->view('tube.search', [
            'scenes' => $scenes,
        ]);
    }

    /**
     * @param $profile
     * @param $permalinkCategory
     * @param int $page
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function category($profile, $permalinkCategory, $page = 1, Request $request)
    {
        $categoryTranslation = Category::getTranslationFromPermalink(
            $permalinkCategory,
            App::make('sexodomeKernel')->getSite()->id,
            App::make('sexodomeKernel')->getLanguage()->id
        );

        if (!$categoryTranslation) {
            abort(404, 'Category not found');
        }

        if ($categoryTranslation->category->status != 1) {
            abort(404, 'Category not found');
        }

        // get scenes
        $scenes = Scene::getTranslationsForCategory(
                $categoryTranslation->category->id,
                App::make('sexodomeKernel')->language->id,
                $request->input('order', false)
            )
            ->paginate(App::make('sexodomeKernel')->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.category', [
            'scenes'              => $scenes,
            'categoryTranslation' => $categoryTranslation,
            'permalinkCategory'   => $permalinkCategory,
            'page'                => $page
        ]);
    }

    public function pornstars($profile, $page = 1)
    {
        $pornstars = Pornstar::where('site_id', App::make('sexodomeKernel')->site->id)
            ->paginate(App::make('sexodomeKernel')->perPageCategories, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.pornstars', [
            'pornstars' => $pornstars,
            'page'      => $page,
        ]);
    }

    public function pornstar($profile, $permalinkPornstar, $page = 1)
    {
        $pornstar = Pornstar::where('pornstars.site_id', '=', App::make('sexodomeKernel')->site->id)
            ->where('permalink', $permalinkPornstar)
            ->first()
        ;

        if (!$pornstar) {
            abort(404, "Pornstar not found");
        }

        $scenes = Scene::getTranslationsForPornstar($pornstar->id, App::make('sexodomeKernel')->language->id)
            ->paginate(App::make('sexodomeKernel')->perPageScenes, $columns = ['*'], $pageName = 'page', $page)
        ;

        return response()->view('tube.pornstar', [
            'scenes'   => $scenes,
            'pornstar' => $pornstar,
        ]);
    }

    public function video($profile, $permalink)
    {
        $scene = Scene::getTranslationByPermalink($permalink, App::make('sexodomeKernel')->language->id, App::make('sexodomeKernel')->site->id);

        if (!$scene) {
            abort(404, 'Scene not found');
        }

        Scene::addSceneClick($scene);

        if ($scene->tags()->count() > 0) {
            $randomTag = $scene->tags()->orderByRaw("RAND()")->first();
            $tag = $randomTag->translations()->where('language_id', App::make('sexodomeKernel')->language->id)->first();
            $related = Scene::getTranslationsForTag($tag->name, App::make('sexodomeKernel')->language->id, true);
            if (count($related) == 0) {
                $related = Scene::getAllTranslated(App::make('sexodomeKernel')->language->id);
            }
        } else {
            $related = Scene::getAllTranslated(App::make('sexodomeKernel')->language->id);
        }

        return response()->view('tube.video', [
            'video'   => $scene,
            'related' => $related->orderBy('rate', 'desc')->limit(4)->get(),
        ]);
    }

    public function iframe($profile, $scene_id)
    {
        $scene = Scene::find($scene_id);

        if (!$scene) {
            abort(404, 'Scene not found');
        }
        return view('tube.iframe', [
            'scene' => $scene,
        ]);
    }

    public function ads($profile)
    {
        $categories = App::make('sexodomeKernel')->site->categories()->where('status', 1)->limit(18)->get();

        return response()->view('tube.ads', [
            'categories' => $categories,
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

    public function sitemap()
    {
        if (App::make('sexodomeKernel')->site) {
            $sitemapFile = App::make('sexodomeKernel')->site->getSitemap();
            $file = Storage::disk('web')->get($sitemapFile);
        } else {
            $file = Storage::disk('web')->get('sexodome-sitemap.xml');
        }

        return response($file, "200")->header('Content-Type', "application/xml");
    }

    public function dmca($profile)
    {
        return response()->view('tube.static.dmca');
    }

    public function terms($profile)
    {
        return response()->view('tube.static.terms');
    }

    public function C2257($profile)
    {
        return response()->view('tube.static.2257');
    }
}
