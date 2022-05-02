<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\CronJob;
use Sexodome\Shared\Application\Command\CommandHandler;

class DeleteCronjobCommandHandler implements CommandHandler
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
