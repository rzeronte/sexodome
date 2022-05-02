<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Tag;
use Sexodome\Shared\Application\Command\CommandHandler;

class DeleteTagCommandHandler implements CommandHandler
{
    public function execute($tag_id)
    {
        try {
            $tag = Tag::find($tag_id);

            if (!$tag) {
                return [ 'status' => false, 'message' => "Tag $tag_id not found" ];
            }

            $tag->delete();

            return ['status' => true ];
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage() ];
        }
    }
}
