<?php

namespace Sexodome\Shared\Infrastructure\Rest\Api;

use App\Model\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Sexodome\SexodomeApi\Application\UpdateTagTranslationCommandHandler;
use Sexodome\Shared\Infrastructure\Rest\AuthorizedController;

class SaveTagTranslationPage extends AuthorizedController
{
    public function __invoke($tag_id): JsonResponse
    {
        $tag = Tag::findOrFail($tag_id);

        return new JsonResponse((new UpdateTagTranslationCommandHandler())->execute(
            $tag_id,
            $tag->site->language->id,
            Request::input('language_' . $tag->site->language->id),
            Request::input('status', false)
        ));
    }
}
