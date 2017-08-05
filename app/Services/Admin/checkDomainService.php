<?php

namespace App\Services\Admin;

use App\Model\Site;

class checkDomainService
{
    public function execute($domain)
    {
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $domain))
        {
            return [ 'status' => false, 'message' => "$domain have not valid valid characteres"];
        }

        if (strlen($domain) == 0) {
            return [ 'status' => false, 'message' => "Domain $domain too short!"];
        }

        if (count(explode('.', $domain)) !== 2) {
            return [ 'status' => false, 'message' => "$domain is not valid first level domain"];
        }

        if (strpos($domain, 'http:') !== false || strpos($domain, 'https:') !== false) {
            return [ 'status' => false, 'message' => "Domain $domain should not include protocol"];
        }

        $sites = Site::where('domain', '=', $domain)->count();

        if ($sites == 0) {
            return [
                'status'  => true,
                'message' => "Domain '$domain' is available"
            ];
        } else {
            return [
                'status'  => false,
                'message' => "Domain '$domain' is already in use"
            ];
        }
    }

    public function isValidDomain($domain) {

    }
}