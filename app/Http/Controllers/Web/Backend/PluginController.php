<?php

namespace App\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PluginController extends Controller
{

    public function index()
    {
        return view('backend.layouts.plugins.index');
    }

    public function install(Request $request)
    {
        $path = public_path('uploads/plugins/');
        $plugin = base64_decode($request->plugin);
        $url = $path . $plugin;
        $zip = new \ZipArchive();
        $res = $zip->open($url);
        if ($res === true) {
            $zip->extractTo(base_path('plugins/'));
            $zip->close();
            return back()->with('t-success', 'Plugin installed successfully.');
        } else {
            return back()->with('t-error', 'Failed to open the zip file.');
        }
        
    }
}
