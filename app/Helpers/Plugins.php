<?php

namespace App\Helpers;

class Plugins
{

    public static function getPluginsList()
    {
        $folders = scandir(__DIR__ . '/../../plugins/');
        foreach ($folders as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }

            $pluginRoutesPath = base_path('plugins/' . $folder . '/routes/route.php');
            if (file_exists($pluginRoutesPath) && is_readable($pluginRoutesPath)) {
                echo "<li><a href='/plugins/$folder/index' class='slide-item'>$folder</a></li>";
            } else {
                echo "<li><a href='#' class='slide-item'>not found</a></li>";
            }
        }
    }
}
