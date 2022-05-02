<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use App\Model\Category;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowSiteCategoriesCommandHandler implements CommandHandler
{
    public function execute($site_id, $query_string, $perPage, $order = false)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found" ];
        }

        $categories = Category::getTranslationSearch(
            $query_string,
            $site->language->id,
            $site->id,
            $order
        )->paginate($perPage);

        return [
            'status'     => true,
            'message'    => 'showSiteCategoriesCommandHandler has been executed',
            'site'       => $site,
            'categories' => $categories,
        ];
    }
}
