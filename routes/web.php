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
