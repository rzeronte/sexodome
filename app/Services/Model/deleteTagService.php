<?php

namespace DDD\Application\Service\Admin;

class deleteTagService
{
    public function execute($tag_id)
    {
        try {
            $tag = Tag::findOfFail($tag_id);
            $tag->delete();
            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false]);
        }
    }
}