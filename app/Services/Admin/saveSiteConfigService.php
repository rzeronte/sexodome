<?php

namespace App\Services\Admin;

use App\Model\Site;

class saveSiteConfigService
{
    public function execute($site_id, $data)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $site->status = $data['status'];
        $site->language_id = $data['language_id'];
        $site->contact_email = $data['contact_email'];
        $site->type_id = $data['type_id'];

        $site->domain = $data['domain'];
        $site->link_billboard = $data['link_billboard'];
        $site->link_billboard_mobile = $data['link_billboard_mobile'];
        $site->google_analytics = $data['google_analytics'];
        $site->javascript = $data['javascript'];

        //**

        $site->seo->category_url = $data['category_url'];
        $site->seo->pornstars_url = $data['pornstars_url'];
        $site->seo->pornstar_url = $data['pornstar_url'];
        $site->seo->video_url = $data['video_url'];
        $site->seo->logo_h1 = $data['logo_h1'];
        $site->seo->h2_home = $data['h2_home'];
        $site->seo->h2_category = $data['h2_category'];
        $site->seo->h2_pornstars = $data['h2_pornstars'];
        $site->seo->h2_pornstar = $data['h2_pornstar'];
        $site->seo->categories_h3 = $data['categories_h3'];

        $site->seo->title_index = $data['title_index'];
        $site->seo->title_category = $data['title_category'];
        $site->seo->description_index = $data['description_index'];
        $site->seo->description_category = $data['description_category'];
        $site->seo->title_pornstars = $data['title_pornstars'];
        $site->seo->title_pornstar = $data['title_pornstar'];
        $site->seo->description_pornstars = $data['description_pornstars'];
        $site->seo->description_pornstar = $data['description_pornstar'];
        $site->seo->title_topscenes = $data['title_topscenes'];
        $site->seo->description_topscenes = $data['description_topscenes'];
        $site->seo->title_tag = $data['title_tag'];
        $site->seo->description_tag = $data['description_tag'];
        $site->seo->header_text = $data['header_text'];

        try {
            $site->save();
            $site->seo->save();
            return [ 'status' => true ];
        } catch (\Exception $e){
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}