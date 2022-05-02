<?php

namespace Sexodome\SexodomeTube\Application;

use App\rZeBot\sexodomeKernel;

class getSitemapService
{
    public function execute()
    {
        return sexodomeKernel::getSitemapFile();
    }
}
