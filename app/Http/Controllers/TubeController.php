<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Model\Category;
use App\Model\Scene;
use App\Model\Pornstar;
use Illuminate\Support\Facades\Cache;
use App\rZeBot\sexodomeKernel;

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

        $categoriesAlphabetical = Category::getForTranslation(
                $status = true,
                App::make('sexodomeKernel')->site->id,
                App::make('sexodomeKernel')->language->id,
                $limit = 120
            )
            ->get()
        ;

        return response()->view('tube.categories', [
            'categories' => $categories,
            'page'       => $page,
            'categoriesAlphabetical' => $categoriesAlphabetical
        ]);
    }

    public function search($profile, Request $request)
    {
        $scenes = Scene::getTranslationSearch(
                $request->input('q', false),
                App::make('sexodomeKernel')->language->id,
                App::make('sexodomeKernel')->site->id,
                $status = true
            )
            ->paginate(App::make('sexodomeKernel')->perPage)
        ;

        return response()->view('tube.search', [
            'scenes' => $scenes,
        ]);
    }

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
        $pornstar = Pornstar::getPornstarByPermalink($permalinkPornstar, App::make('sexodomeKernel')->site->id);

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
        // all valid languages
        $scene = Cache::remember(App::make('sexodomeKernel')->language->id . "_" . $permalink, env('MEMCACHED_QUERY_TIME', 30), function() use ($permalink){
            return Scene::getTranslationByPermalink($permalink, App::make('sexodomeKernel')->language->id, App::make('sexodomeKernel')->site->id);
        });

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
        $scene = Scene::findOrFail($scene_id);

        return view('tube.iframe', ['scene' => $scene]);
    }

    public function ads($profile)
    {
        return response()->view('tube.ads', [
            'categories' => App::make('sexodomeKernel')->site->categories()->where('status', 1)->limit(18)->get()
        ]);
    }

    public function out($profile, $scene_id, Request $request)
    {
        $scene = Scene::findOrFail($scene_id);

        Scene::addSceneClick($scene, $ua = $request->header('User-Agent'));

        return redirect($scene->url);
    }

    public function sitemap()
    {
        return sexodomeKernel::getSitemapFile();
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
