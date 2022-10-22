<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $image = Image::query()->create([
            'source' => 'nfgnfgn',
            'size' => 43,
        ]);
        $author = Author::query()->create([
            'name' => 'iojk',
        ]);
        $comic = Comic::query()->create([
            'name' => 'aa',
            'thumbnail_id' => $image->id,
            'banner_id' => $image->id,
            'description' => 'aa',
            'status' => 1,
            'author_id' => $author->id,
        ]);
        for ($j = 0; $j <= 10000; $j++) {
            $arr = [];
            for ($i= 0; $i <= 1000; $i++) {
                $arr[] = [
                    'id' => Str::random(),
                    'chap' => $i,
                    'content' => 'https://stackoverflow.com/questions/45255210/array-slice-function-with-a-foreach-loop',
                    'comic_id' => $comic->id,
                ];
            }
            Chapter::query()->insert($arr);
        }

    }
}
