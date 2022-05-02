<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Category;
use Sexodome\Shared\Application\Command\CommandHandler;

class UpdateCategoryTagsCommandHandler implements CommandHandler
{
    public function execute($category_id, $tag_ids)
    {
        $category = Category::find($category_id);

        if ($category) {
            $category->tags()->sync($tag_ids);

            return [ 'status' => true ];
        } else {
            return [ 'status' => false, 'message' => 'Category not found' ];
        }
    }
}
