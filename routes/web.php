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

//    $client = new Client();
    (new ComicController())->clone();
    dd('xongggggg');
//    (new ComicController())->clone();




});

Route::get('/d', function () {
    $s3_paths = Storage::disk('s3')->directories('Comics');
    $db_paths = Comic::query()->get()->pluck('id')->map(static function ($id) {
        return 'Comics/'.$id;
    })->toArray();
    $diffs = array_diff($s3_paths, $db_paths);

    foreach ($diffs as $diff) {
        Storage::disk('s3')->deleteDirectory($diff);
    }
    dd('These are deleted:', $diffs);

});
