<?php

namespace DDD\Application\Service\Admin;

class saveSiteLogoService
{
    public function execute($site_id, Request $request, $logosFolder, $faviconsFolder, $headersFolder)
    {

        $site = Site::findOrFail($site_id);

        $delete_header = $request->input('header_delete');

        // logo validator
        $v = Validator::make($request->all(), [
            'logo' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // favicon validator
        $vF = Validator::make($request->all(), [
            'favicon' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        // favicon validator
        $vH = Validator::make($request->all(), [
            'header' => 'required|mimes:png',      // max=50*1024; min=3*1024
        ]);

        $v->after(function ($validator) {
            $extensions_acepted = ["png"];
            $extension = Input::file('logo')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                return json_encode(['status' => true, 'message' => 'Logo invalid']);
            }
        });

        $vF->after(function ($validator) {
            $extensions_acepted = ["png"];
            $extension = Input::file('favicon')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                return json_encode(['status' => true, 'message' => 'Favicon invalid']);
            }
        });

        $vH->after(function ($validator) {
            $extensions_acepted = ["png"];
            $extension = Input::file('header')->getClientOriginalExtension();

            if (!in_array(strtolower($extension), $extensions_acepted)) {
                return json_encode(['status' => true, 'message' => 'Header invalid']);
            }
        });

        if ($request->hasFile('logo') && !$v->fails()) {
            $request->file('logo')->move($logosFolder, md5($site_id) . "." . $request->file('logo')->getClientOriginalExtension());
        } else {
            $request->session()->flash('error', 'Upload invalid file. Check your Logo file, size ane extension (pngs only)!');
        }

        if ($request->hasFile('favicon') && !$vF->fails()) {
            $request->file('favicon')->move($faviconsFolder, md5($site_id) . "." . $request->file('favicon')->getClientOriginalExtension());
        } else {
            $request->session()->flash('error', 'Upload invalid file. Check your Favicon file, size ane extension (pngs only)!');
        }

        if ($request->hasFile('header') && !$vH->fails() && $delete_header != 1) {
            $request->file('header')->move($headersFolder, md5($site_id) . "." . $request->file('header')->getClientOriginalExtension());
        } else {
            if ($delete_header == 1) {
                unlink($headersFolder . md5($site_id) . ".png");
            } else {
                return json_encode(['status' => false, 'message' => 'Upload invalid file. Check your Header file, size ane extension (png only)!']);
            }
        }

        return json_encode(['status' => true]);
    }
}