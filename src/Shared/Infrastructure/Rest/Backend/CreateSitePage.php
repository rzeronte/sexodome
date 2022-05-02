<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use App\Model\Site;
use Illuminate\Support\Facades\Auth;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CreateSitePage extends AuthorizedController
{
    public function __invoke()
    {
        return view('panel.add_site', ['sites' => Site::where('user_id', '=', Auth::user()->id)->get()]);
    }
}
