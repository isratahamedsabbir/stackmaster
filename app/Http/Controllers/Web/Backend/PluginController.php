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

            $app = null;
            $version = null;
            $filesInZip = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $filesInZip[] = $stat['name'];
            }
            foreach ($filesInZip as $fileInZip) {
                if (strpos($fileInZip, 'info.json') !== false) {
                    $pluginInfoJson = $zip->getFromName($fileInZip);
                    $pluginInfo = json_decode($pluginInfoJson, true, 512, JSON_THROW_ON_ERROR);
                    if ($pluginInfo !== null) {
                        $app = $pluginInfo['app'] ?? null;
                        $version = $pluginInfo['version'] ?? null;
                    }

                    break;
                }
            }

            $zip->extractTo(base_path('plugins/'.$app.'v'.$version.'/'));
            $zip->close();
            return back()->with('t-success', 'Plugin installed successfully.');
        } else {
            return back()->with('t-error', 'Failed to open the zip file.');
        }
    }
}
