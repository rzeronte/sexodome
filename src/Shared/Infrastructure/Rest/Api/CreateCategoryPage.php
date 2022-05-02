<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use App\Model\Language;
use App\Model\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\CreateCategoryCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class CreateCategoryPage extends AuthorizedController
{
    public function __invoke($site_id): JsonResponse
    {
        return new JsonResponse((new CreateCategoryCommandHandler())->execute(
            $site_id,
            Request::input('language_en'),
            $this->prepareCategoryRequestData($site_id)
        ));
    }

    protected function prepareCategoryRequestData($site_id): array
    {
        $languagesData  = [];
        foreach(Language::getAddLanguages(Site::findOrFail($site_id)->language_id) as $language) {
            $languagesData[$language->code] = Request::input('language_'.$language->code);
        }

        return $languagesData;
    }
}
