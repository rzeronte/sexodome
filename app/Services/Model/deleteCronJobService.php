<?php

namespace App\Services\Model;

use App\Model\CronJob;

class deleteCronJobService
{
    public function execute($cronjob_id)
    {
        try {
            $cronjob = CronJob::findOrFail($cronjob_id);
            $cronjob->delete();

            return ['status' => true];
        } catch(\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}