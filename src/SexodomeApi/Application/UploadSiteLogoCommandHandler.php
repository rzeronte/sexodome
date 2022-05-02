<?php

namespace Sexodome\SexodomeApi\Application;

use Illuminate\Support\Facades\Validator;
use App\Model\Site;
use Illuminate\Support\Facades\Request;
use Sexodome\Shared\Application\Command\CommandHandler;

class UploadSiteLogoCommandHandler implements CommandHandler
{
    public function execute($site_id, $logosFolder, $faviconsFolder, $headersFolder)
    {
        $site = Site::find($site_id);

        if (!$site) {
            return ['status' => false, 'message' => "Site $site_id not found"];
        }

        $delete_header = Request::input('header_delete');

        // logo validator
        $v = Validator::make(Request::all(), [
            'logo' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // favicon validator
        $vF = Validator::make(Request::all(), [
            'favicon' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // header validator
        $vH = Validator::make(Request::all(), [
            'header' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        if (!$v->fails() && !$vF->fails() && !$vH->fails()) {
            return ['status' => false, 'message' => 'Check file extensions. Only PNG and JPG files'];;
        }

        $files = [];
        if (Request::hasFile('logo')) {
            if (!$v->fails()) {
                $fileName = md5($site_id) . "." . Request::file('logo')->getClientOriginalExtension();
                Request::file('logo')->move($logosFolder, $fileName);
                $files[] = ['logo_url' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/logos/" . $fileName];
            } else {
                return ['status' => false, 'message' => 'Check logo image'];
            }
        }

        if (Request::hasFile('favicon')) {
            if (!$vF->fails()) {
                $fileName = md5($site_id) . "." . Request::file('favicon')->getClientOriginalExtension();
                Request::file('favicon')->move($faviconsFolder, $fileName);
                $files[] = ['favicon_url' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/favicons/" .  $fileName];
            } else {
                return ['status' => false, 'message' => 'Check favicon image'];;
            }
        }

        if (Request::hasFile('header')) {
            if (!$vH->fails()) {
                $fileName = md5($site_id) . "." . Request::file('header')->getClientOriginalExtension();
                Request::file('header')->move($headersFolder, $fileName);
                $files[] = ['header_url' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/headers/" . $fileName];
            } else {
                return ['status' => false, 'message' => 'Check header image'];;
            }
        }

        try {
            if ($delete_header == 1 and file_exists($headersFolder . md5($site_id) . ".png")) {
                unlink($headersFolder . md5($site_id) . ".png");
            }
        } catch (\Exception $e){
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }

        return [
            'status'  => true,
            'message' => 'File has been uploaded',
            "files"   => $files
        ];
    }
}
