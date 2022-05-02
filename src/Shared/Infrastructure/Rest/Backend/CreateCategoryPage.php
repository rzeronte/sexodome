<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use App\Model\Site;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CreateCategoryPage extends AuthorizedController
{
    public function __invoke($site_id)
    {
        return view('panel.ajax._ajax_site_create_category', ['site' => Site::findOrFail($site_id)]);
    }
}
