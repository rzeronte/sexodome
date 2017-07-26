<?php

namespace DDD\Application\Service\Admin;

class addSiteService
{
    public function execute($domain)
    {
        // check if already exists
        $site = Site::where('domain', '=', trim($domain))->first();

        if ($site) {
            return json_encode([
                'status'  => $status = false,
                'message' => 'Domain ' . trim($domain) . ' already exists!'
            ]);
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

            return json_encode(['status' => true ]);

        } catch(\Exception $e) {
            return json_encode(['status' => false ]);
        }
    }
}