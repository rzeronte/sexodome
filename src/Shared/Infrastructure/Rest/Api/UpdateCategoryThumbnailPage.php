<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use Illuminate\Http\JsonResponse;
use Sexodome\SexodomeApi\Application\UploadCategoryThumbnailCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class UpdateCategoryThumbnailPage extends AuthorizedController
{
    public function __invoke($category_id): JsonResponse
    {
        return new JsonResponse((new UploadCategoryThumbnailCommandHandler())->execute($category_id));
    }
}
