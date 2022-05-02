<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Illuminate\Http\RedirectResponse;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class HomePage extends AuthorizedController
{

    public function __invoke(): RedirectResponse
    {
        return redirect()->route('sites');
    }
}
