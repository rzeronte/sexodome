<?php

namespace App\Model;

use App\rZeBot\sexodomeKernel;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';

    public $timestamps = false;

    public function scenes()
    {
        return $this->hasMany('App\Model\Scene');
    }

    public function existDump()
    {
        // Added ".." extra (for model folder)
        $fileCSV = "../" . sexodomeKernel::getDumpsFolder().$this->file;

        if (file_exists($fileCSV)) {
            return true;
        }

        return false;
    }
}
