<?php

namespace Sexodome\SexodomeApi\Application;

use Illuminate\Support\Facades\Auth;
use Sexodome\Shared\Application\Command\CommandHandler;

class UnverifiedUserCommandHandler implements CommandHandler
{
    public function execute()
    {
        Auth::logout();

        return [ 'status' => true, 'message' => 'User set as not verified'];
    }
}
