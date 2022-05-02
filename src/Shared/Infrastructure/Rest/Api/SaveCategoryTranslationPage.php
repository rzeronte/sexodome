<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use App\Model\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\UpdateCategoryTranslationCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class SaveCategoryTranslationPage  extends AuthorizedController
{

    public function __invoke($category_id): JsonResponse
    {
        $category = Category::findOrFail($category_id);

        return new JsonResponse((new UpdateCategoryTranslationCommandHandler())->execute(
            $category_id,
            $category->site->language_id,
            Request::input('language_' . $category->site->language->id),
            Request::input('thumbnail'),
            $status = true
        ));
    }
}
