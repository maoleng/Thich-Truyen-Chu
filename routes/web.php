<?php

use App\Http\Controllers\ComicController;
use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Image;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


Route::get('/', function () {
    set_time_limit(6000);

//    $client = new Client();
    (new ComicController())->clone();
    dd('xongggggg');
//    (new ComicController())->clone();




});

Route::get('/d', function () {
    $a = Storage::disk('s3')->allFiles();
    foreach ($a as $file) {
        Storage::disk('s3')->delete($file);
    }
    dd(Storage::disk('s3')->allFiles());

});
