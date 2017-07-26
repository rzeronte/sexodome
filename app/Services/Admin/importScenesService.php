<?php

namespace DDD\Application\Service\Admin;

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

            return json_encode(['status' => true]);

        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}