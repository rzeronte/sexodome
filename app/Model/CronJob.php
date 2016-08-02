<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CronJob extends Model
{
    protected $table = 'cronjobs';

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }

    public function channel()
    {
        return $this->belongsTo('App\Model\Channel');
    }

    public static function getUserCronJobs()
    {
        return CronJob::select('cronjobs.*')
            ->join('sites', 'sites.id', '=', 'cronjobs.site_id')
            ->join('users', 'users.id', '=', 'sites.user_id')
            ->where('users.id', '=', Auth::user()->id)
            ->groupBy('cronjobs.id')
            ->orderBy('cronjobs.id', 'DESC')
            ;
    }
}
