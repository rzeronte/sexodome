<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use App\Model\Language;
use App\Model\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\CreateTagCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CreateTagPage extends AuthorizedController
{
    public function __invoke($site_id): JsonResponse
    {
        return new JsonResponse((new CreateTagCommandHandler())->execute($site_id, $this->prepareTagRequestData($site_id)));
    }

    protected function prepareTagRequestData($site_id): array
    {
        $languagesData  = [];
        foreach(Language::getAddLanguages(Site::findOrFail($site_id)->language_id) as $language) {
            $languagesData[$language->code] = Request::input('language_'.$language->code);
        }

        return $languagesData;
    }
}
