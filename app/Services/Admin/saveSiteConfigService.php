<?php

namespace DDD\Application\Service\Admin;

class saveSiteConfigService
{
    public function execute($site_id, $data)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return json_encode(['status' => true, 'message' => 'Site not found']);
        }

        $site->status = $data['status'];

        $site->language_id = $data['language_id'];

        $site->category_url = $data['category_url'];
        $site->pornstars_url = $data['pornstars_url'];
        $site->pornstar_url = $data['pornstar_url'];
        $site->video_url = $data['video_url'];

        $site->contact_email = $data['contact_email'];

        $site->type_id = $data['type_id'];

        $site->logo_h1 = $data['logo_h1'];
        $site->categories_h3 = $data['categories_h3'];
        $site->h2_home = $data['h2_home'];
        $site->h2_category = $data['h2_category'];
        $site->h2_pornstars = $data['h2_pornstars'];
        $site->h2_pornstar = $data['h2_pornstar'];

        $site->title_index = $data['title_index'];
        $site->title_category = $data['title_category'];

        $site->description_index = $data['description_index'];
        $site->description_category = $data['description_category'];

        $site->title_pornstars = $data['title_pornstars'];
        $site->title_pornstar = $data['title_pornstar'];

        $site->description_pornstars = $data['description_pornstars'];
        $site->description_pornstar = $data['description_pornstar'];

        $site->domain = $data['domain'];
        $site->header_text = $data['header_text'];
        $site->link_billboard = $data['link_billboard'];
        $site->link_billboard_mobile = $data['link_billboard_mobile'];

        $site->google_analytics = $data['google_analytics'];

        $site->javascript = $data['javascript'];

        $site->save();

        return json_encode(['status' => true]);
    }
}