<?php

namespace DDD\Application\Service\Admin;

class showSiteService
{
    public function execute($site_id)
    {
        $site = Site::findOrFail($site_id);

        // Si el idioma es distinto, actualizamos locale e idioma
        if ($site->language->code != App::getLocale()) {
            App::setLocale($site->language->code);
        }

        $ff = date("Y-m-d");
        $fi = date("Y-m-d", strtotime($ff . " -50 days"));

        return [
            'channels'  => Channel::all(),
            'site'      => $site,
            'sites'     => Auth::user()->getSites(),
            'fi'        => $fi,
            'ff'        => $ff,
            'types'     => Type::all(),
        ];
    }
}