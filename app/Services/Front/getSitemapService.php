<?php

namespace App\Services\Front;

use App\rZeBot\sexodomeKernel;

class getSitemapService
{
    public function execute()
    {
        return sexodomeKernel::getSitemapFile();
    }
}