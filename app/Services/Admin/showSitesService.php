<?php

namespace DDD\Application\Service\Admin;

class showSitesService
{
    public function execute($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return false;
        }

        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff . " -30 days"));

        $sites = Auth::user()->getSites();

        return [
            'channels' => Channel::all(),
            'title'    => "Admin Panel",
            'sites'    => $sites,
            'fi'       => $fi,
            'ff'       => $ff,
        ];
    }
}