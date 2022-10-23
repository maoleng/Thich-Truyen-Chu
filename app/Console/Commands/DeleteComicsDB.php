<?php

namespace App\Console\Commands;

use App\Models\Comic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteComicsDB extends Command
{
    protected $signature = 'delete:comic_db';
    protected $description = 'Xóa những truyện có trong db nhưng không có trên s3';

    public function handle()
    {
        /*
        * Route này dùng để xóa những truyện có trong db nhưng không có trên s3
        * array_diff($db_paths, $s3_paths)
        */
        $s3_paths = Storage::disk('s3')->directories('Comics');
        $db_paths = Comic::query()->get()->pluck('id')->map(static function ($id) {
            return 'Comics/'.$id;
        })->toArray();
        $diffs = array_diff($db_paths, $s3_paths);
        $ids = array_map(static function ($diff) {
            return substr($diff, 7);
        }, $diffs);
        foreach ($ids as $id) {
            $comic = Comic::query()->where('id', $id)->first();
            $comic->author->delete();
            $comic->thumbnail->delete();
            $comic->banner->delete();
            $comic->delete();
        }
        dd('These are deleted out of database:', $ids);
    }
}
