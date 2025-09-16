<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\PluginController;

Route::middleware(['web'])->group(function () {
    Route::get('/plugins/index', [PluginController::class, 'index'])->name('plugins.index');
    Route::get('/plugins/upload', [PluginController::class, 'upload'])->name('plugins.upload');
    Route::get('/plugins/install/{plugin}', [PluginController::class, 'install'])->name('plugins.install');
    Route::get('/plugins/unstall/{plugin}', [PluginController::class, 'unstall'])->name('plugins.unstall');
});

$folders = scandir(__DIR__ . '/../plugins/');
foreach ($folders as $folder) {
    if ($folder === '.' || $folder === '..') {
        continue;
    }

    $pluginRoutesPath = base_path('plugins/' . $folder . '/routes/route.php');
    if (file_exists($pluginRoutesPath) && is_readable($pluginRoutesPath)) {
        require $pluginRoutesPath;
    } else {
        throw new \RuntimeException("Plugin $folder does not have a readable route.php file");
    }
}
