<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Site;
use App\Model\CronJob;
use App\Model\Channel;
use Sexodome\Shared\Application\Command\CommandHandler;

class CreateCronjobCommandHandler implements CommandHandler
{
    public function execute($feed_name, $site_id, $parameters)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return [ 'status' => false, 'message' => "Site $site_id not found"];
        }

        $channel = Channel::where('name', $feed_name)->first();

        if (!$channel) {
            return [ 'status' => false, 'message' => 'Channel not found'];
        }

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

            return ['status' => true];
        } catch(\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
    }
}
