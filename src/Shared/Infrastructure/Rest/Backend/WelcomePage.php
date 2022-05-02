<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class WelcomePage extends AuthorizedController
{
    public function __invoke()
    {
        return view('panel.welcome');
    }
}
