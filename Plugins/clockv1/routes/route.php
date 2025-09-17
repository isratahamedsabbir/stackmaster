<?php
use Illuminate\Support\Facades\Route;

$info = json_decode(file_get_contents(__DIR__ . "/../info.json"), true);
Route::get('/plugins/'.$info['app'].'v'.$info['version'].'/index', function () {
    return "Custom Route Working!";
});