<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\SaveSiteColorsCommandHandler;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class UpdateColorsPage  extends AuthorizedController
{

    public function __invoke($site_id): JsonResponse
    {
        return new JsonResponse((new SaveSiteColorsCommandHandler())->execute($site_id, [
            'color' => Request::input('color') != "" ? Request::input('color') : null,
            'color2' => Request::input('color2') != "" ? Request::input('color2') : null,
            'color3' => Request::input('color3') != "" ? Request::input('color3') : null,
            'color4' => Request::input('color4') != "" ? Request::input('color4') : null,
            'color5' => Request::input('color5') != "" ? Request::input('color5') : null,
            'color6' => Request::input('color6') != "" ? Request::input('color6') : null,
            'color7' => Request::input('color7') != "" ? Request::input('color7') : null,
            'color8' => Request::input('color8') != "" ? Request::input('color8') : null,
            'color9' => Request::input('color9') != "" ? Request::input('color9') : null,
            'color10' => Request::input('color10') != "" ? Request::input('color10') : null,
            'color11' => Request::input('color11') != "" ? Request::input('color11') : null,
            'color12' => Request::input('color12') != "" ? Request::input('color12') : null,
        ]));
    }
}
