<?php

namespace DDD\Application\Service\Admin;

class deleteSiteService
{
    public function execute($site_id)
    {
        $site = Site::findOrFail($site_id);

        if (!(Auth::user()->id == $site->user->id)) {
            abort(401, "Unauthorized");
        }

        $site->delete();

        return redirect()->route('sites', []);
    }
}