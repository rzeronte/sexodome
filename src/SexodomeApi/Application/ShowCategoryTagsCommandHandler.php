<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Category;
use App\Model\Tag;
use Sexodome\Shared\Application\Command\CommandHandler;

class ShowCategoryTagsCommandHandler implements CommandHandler
{
    public function execute($category_id)
    {
        $category = Category::find($category_id);

        if (!$category) {
            return false;
        }

        $category_tags = Tag::getTranslationByCategory($category, 2)->get()->pluck('id');
        $category_tags = $category_tags->all();

        $site_tags = Tag::getTranslationSearch(false, 2, $category->site->id)->orderBy('permalink', 'asc')->get();

        return [
            'status'        => true,
            'message'       => 'showCategoryTagsCommandHandler for category_id('.$category->id.') has been executed',
            'category'      => $category,
            'category_tags' => $category_tags,
            'tags'          => $site_tags,
        ];
    }
}
