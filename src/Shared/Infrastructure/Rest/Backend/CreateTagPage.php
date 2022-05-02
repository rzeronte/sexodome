<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use App\Model\Language;
use App\Model\Site;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CreateTagPage extends AuthorizedController
{
    public function __invoke($site_id)
    {
        return view('panel.ajax._ajax_site_create_tag', ['site' => Site::findOrFail($site_id)]);
    }
}
