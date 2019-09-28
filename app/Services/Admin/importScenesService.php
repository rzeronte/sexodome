<?php

namespace App\Services\Admin;

use App\Jobs\ImportScenesWorker;

class importScenesService
{
    public function execute($site_id, $feed_name, $parameters)
    {
        $queueParams = [
            'feed_name'  => $feed_name,
            'site_id'    => $site_id,
            'max'        => $parameters['max'],
            'duration'   => $parameters['duration'],
            'tags'       => (strlen($parameters['tags']) == 0) ? 'false' : $parameters['tags'],
        ];

        $queueParams['only_with_pornstars'] = 'false';
        if ($parameters['only_with_pornstars'] == 1) {
            $queueParams['only_with_pornstars'] = 'true';
        }

        try {
            $job = new ImportScenesWorker($queueParams);
            dispatch($job);

            return [ 'status' => true , 'message' => 'ImportScenesWorker launched successfuly'];

        } catch (\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}