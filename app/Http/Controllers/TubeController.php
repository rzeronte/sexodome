<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;

class TubeController extends Controller
{
    public function categories($domain, $page = 1)
    {
        return view('tube.categories', App::make('getCategoriesService')->execute(
            App::make('sexodomeKernel')->getSite()->id,
            App::make('sexodomeKernel')->getLanguage()->id,
            App::make('sexodomeKernel')->perPageCategories,
            $page
        ));
    }

    public function search($domain)
    {
        return view('tube.search', App::make('getSearchService')->execute(
            Request::input('q', false),
            App::make('sexodomeKernel')->getSite()->id,
            App::make('sexodomeKernel')->getLanguage()->id,
            App::make('sexodomeKernel')->perPageScenes
        ));
    }

    public function category($domain, $permalinkCategory, $page = 1)
    {
        $data = App::make('getCategoryService')->execute(
            $permalinkCategory,
            App::make('sexodomeKernel')->getSite()->id,
            App::make('sexodomeKernel')->getLanguage()->id,
            App::make('sexodomeKernel')->perPageScenes,
            $page,
            Request::input('order', false)
        );

        if ($data['status'] == false) {
            abort(404, $data['message']);
        }

        return view('tube.category', $data);
    }

    public function pornstars($domain, $page = 1)
    {
        return view('tube.pornstars', App::make('getPornstarsService')->execute(
            App::make('sexodomeKernel')->getSite()->id,
            App::make('sexodomeKernel')->perPagePornstars,
            $page
        ));
    }

    public function pornstar($domain, $permalinkPornstar, $page = 1)
    {
        return view('tube.pornstar', App::make('getPornstarService')->execute(
            $permalinkPornstar,
            App::make('sexodomeKernel')->getSite()->id,
            App::make('sexodomeKernel')->getLanguage()->id,
            App::make('sexodomeKernel')->perPageScenes,
            $page
        ));
    }

    public function video($domain, $permalink)
    {
        return view('tube.video', App::make('getVideoService')->execute(
            $permalink,
            App::make('sexodomeKernel')->getSite()->id,
            App::make('sexodomeKernel')->getLanguage()->id
        ));
    }

    public function iframe($domain, $scene_id)
    {
        return view('tube.iframe', App::make('getSceneIframeService')->execute($scene_id));
    }

    public function ads($domain)
    {
        return view('tube.ads', App::make('getSiteAdsService')->execute(
            App::make('sexodomeKernel')->getSite()->id
        ));
    }

    public function out($domain, $scene_id)
    {
        return redirect(App::make('runOutService')->execute($scene_id));
    }

    public function sitemap()
    {
        return App::make('getSitemapService')->execute();
    }

    public function dmca($domain)
    {
        return view('tube.static.dmca');
    }

    public function terms($domain)
    {
        return view('tube.static.terms');
    }

    public function C2257($domain)
    {
        return view('tube.static.2257');
    }
}
