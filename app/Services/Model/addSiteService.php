<?php

namespace App\Services\Model;

use App\Model\Site;
use Illuminate\Support\Facades\Auth;

class addSiteService
{
    public function execute($domain)
    {
        $site = Site::where('domain', '=', trim($domain))->first();

        if ($site) {
            return [
                'status'  => false,
                'message' => 'Domain ' . trim($domain) . ' already exists!'
            ];
        }

        try {
            // create new site for current user
            $newSite = new Site();
            $newSite->user_id = Auth::user()->id;
            $newSite->name = $domain;
            $newSite->language_id = env("DEFAULT_FETCH_LANGUAGE", 2);
            $newSite->domain = $domain;
            $newSite->have_domain = 1;
            $newSite->header_text = "";
            $newSite->save();

            return [
                'status' => true,
                'sites'  => Site::where('user_id', '=', Auth::user()->id)->get()
            ];

        } catch(\Exception $e) {
            echo $e->getMessage();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}