<?php

namespace App\Services\Admin;

use App\User;
use App\Model\Channel;

class getSitesService
{
    public function execute($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return [];
        }

        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff . " -30 days"));

        $sites = $user->getSites();

        return [
            'status'   => true,
            'message'  => 'A total of' . count($sites) . " are returned",
            'channels' => Channel::all(),   // wtf?
            'title'    => "Admin Panel",
            'sites'    => $sites,
            'fi'       => $fi,
            'ff'       => $ff,
        ];
    }
}