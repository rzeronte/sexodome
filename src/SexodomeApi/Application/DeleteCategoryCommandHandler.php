<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Category;
use Sexodome\Shared\Application\Command\CommandHandler;

class DeleteCategoryCommandHandler implements CommandHandler
{
    public function execute($category_id)
    {
        try {
            $category = Category::find($category_id);

            if (!$category) {
                return [ 'status' => false, 'message' => "Category $category_id not found" ];
            }

            $category->delete();

            return ['status' => true ];
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage() ];
        }
    }
}
