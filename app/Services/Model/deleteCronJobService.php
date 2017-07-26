<?php

namespace DDD\Application\Service\Admin;

class deleteCronJobService
{
    public function execute($cronjob_id)
    {
        try {
            $cronjob = CronJob::findOrFail($cronjob_id);

            if (!(Auth::user()->id == $cronjob->site->user->id)) {
                abort(401, "Unauthorized");
            }

            $cronjob->delete();
            $status = true;
        } catch(\Exception $e) {
            $status = false;
        }

        return json_encode(['status' => $status]);
    }
}