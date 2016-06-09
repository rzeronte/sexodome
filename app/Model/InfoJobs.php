<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class InfoJobs extends Model
{
    protected $table = 'infojobs';

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Model\Site');
    }

    public function channel()
    {
        return $this->belongsTo('App\Model\Channel');
    }

    public static function getUserJobs()
    {
        return InfoJobs::select('infojobs.*')
            ->join('sites', 'sites.id', '=', 'infojobs.site_id')
            ->join('users', 'users.id', '=', 'sites.user_id')
            ->where('users.id', '=', Auth::user()->id)
            ->groupBy('infojobs.id')
            ->orderBy('infojobs.created_at', 'DESC')
        ;
    }
}