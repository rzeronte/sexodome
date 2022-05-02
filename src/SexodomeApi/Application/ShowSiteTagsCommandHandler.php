<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use App\Model\Tag;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowSiteTagsCommandHandler implements CommandHandler
{
    public function execute($site_id, $query_string, $perPage)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $tags = Tag::getTranslationSearch(
            $query_string,
            $site->language->id,
            $site_id
        )->paginate($perPage);

        return [
            'status'  => true,
            'message' => 'showSiteTagsCommandHandler has been executed',
            'site'    => $site,
            'tags'    => $tags
        ];
    }
}
