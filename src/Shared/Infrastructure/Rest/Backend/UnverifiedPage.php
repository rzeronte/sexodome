<?php

namespace Sexodome\Shared\Infrastructure\Rest\Backend;

use Sexodome\SexodomeApi\Application\UnverifiedUserCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class UnverifiedPage extends AuthorizedController
{
    public function __invoke()
    {
        return view('panel.unverified', (new UnverifiedUserCommandHandler())->execute());
    }
}
