<?php

namespace DDD\Application\Service\Admin;

class deletePopunderService
{
    public function execute($popunder_id)
    {
        try {
            $popunder = Popunder::findOrFail($popunder_id);
            $popunder->delete();
            return json_encode(['status' => $status = true]);
        } catch (\Exception $e) {
            return json_encode(['status' => $status = false]);
        }
    }
}