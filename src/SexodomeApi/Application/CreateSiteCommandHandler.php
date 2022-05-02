<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Seo;
use App\Model\Site;
use Illuminate\Support\Facades\Auth;
use Sexodome\Shared\Application\Command\CommandHandler;

class CreateSiteCommandHandler implements CommandHandler
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
            $newSite->type_id = env("DEFAULT_TYPE", 1);
            $newSite->name = $domain;
            $newSite->language_id = env("DEFAULT_FETCH_LANGUAGE", 2);
            $newSite->domain = $domain;
            $newSite->have_domain = 1;
            $newSite->push();

            $seo = new Seo();
            $seo->site()->associate($newSite);
            $seo->save();

            return [
                'status' => true,
                'sites'  => Site::where('user_id', '=', Auth::user()->id)->get()
            ];

        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
