<?php

namespace DDD\Application\Service\Admin;

class addCronjobService
{
    public function execute($feed_name, $site_id, $parameters)
    {
        Site::findOrFail($site_id);

        $channel = Channel::where('name', $feed_name)->firstOrFail();

        try {
            $queueParams = [
                'feed_name' => $feed_name,
                'site_id'   => $site_id,
                'max'       => $parameters['max'],
                'duration'  => $parameters['duration'],
                'tags'      => strlen($parameters['tags']) == 0 ? 'false' : $parameters['tags'],
            ];

            if ($parameters['only_with_pornstars'] == 1) {
                $queueParams['only_with_pornstars'] = 'true';
            } else {
                $queueParams['only_with_pornstars'] = 'false';
            }

            $cronjob = new CronJob();
            $cronjob->site_id = $site_id;
            $cronjob->channel_id = $channel->id;
            $cronjob->params = json_encode($queueParams);
            $cronjob->save();

            return json_encode(['status' => true]);
        } catch(\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}