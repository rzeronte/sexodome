<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use App\Model\Category;
use Illuminate\Support\Facades\Auth;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowOrderCategoriesCommandHandler implements CommandHandler
{
    public function execute($site_id, $limit)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' =>  "Site $site_id not found"];
        }

        $categories = Category::getForTranslation(
            $status = true,
            $site->id,
            $site->language->id,
            $limit
        )->get();

        return [
            'status'     => true,
            'message'    => 'showOrderCategoriesCommandHandler has been executed',
            'sites'      => Auth::user()->getSites(),
            'site'       => Site::find($site_id),
            'categories' => $categories
        ];
    }
}
