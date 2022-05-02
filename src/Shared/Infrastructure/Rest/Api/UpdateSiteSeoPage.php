<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\UpdateSiteConfigCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class UpdateSiteSeoPage extends AuthorizedController
{
    public function __invoke($site_id): JsonResponse
    {
        return new JsonResponse((new UpdateSiteConfigCommandHandler())->execute($site_id, $configData = [
            'status' => Request::input('status'),
            'language_id' => Request::input('language_id'),
            'category_url' => Request::input('category_url'),
            'pornstars_url' => Request::input('pornstars_url'),
            'pornstar_url' => Request::input('pornstar_url'),
            'video_url' => Request::input('video_url'),
            'contact_email' => Request::input('contact_email'),
            'type_id' => Request::input('type_id'),
            'logo_h1' => Request::input('logo_h1'),
            'categories_h3' => Request::input('categories_h3'),
            'h2_home' => Request::input('h2_home'),
            'h2_category' => Request::input('h2_category'),
            'h2_pornstars' => Request::input('h2_pornstars'),
            'h2_pornstar' => Request::input('h2_pornstar'),
            'title_index' => Request::input('title_index'),
            'title_category' => Request::input('title_category'),
            'description_index' => Request::input('description_index'),
            'description_category' => Request::input('description_category'),
            'title_pornstars' => Request::input('title_pornstars'),
            'title_pornstar' => Request::input('title_pornstar'),
            'description_pornstars' => Request::input('description_pornstars'),
            'description_pornstar' => Request::input('description_pornstar'),
            'title_tag' => Request::input('title_tag'),
            'description_tag' => Request::input('description_tag'),
            'title_topscenes' => Request::input('title_topscenes'),
            'description_topscenes' => Request::input('description_topscenes'),
            'domain' => Request::input('domain'),
            'header_text' => Request::input('header_text', ""),
            'link_billboard' => Request::input('link_billboard'),
            'link_billboard_mobile' => Request::input('link_billboard_mobile'),
            'google_analytics' => Request::input('google_analytics'),
            'javascript' => Request::input('javascript')
        ]));
    }
}
