<?php

namespace App\Console\Commands;

use App\Models\Comic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteComicsS3 extends Command
{
    protected $signature = 'delete:comic_s3';
    protected $description = 'Xóa những truyện có trên s3 nhưng không có trong db';

    public function handle()
    {
        /*
         * Route này dùng để xóa những truyện có trên s3 nhưng không có trong db
         * array_diff($s3_paths, $db_paths)
         */
        set_time_limit(0);
        $s3_paths = Storage::disk('s3')->directories('Comics');
        $db_paths = Comic::query()->get()->pluck('id')->map(static function ($id) {
            return 'Comics/'.$id;
        })->toArray();
        $diffs = array_diff($s3_paths, $db_paths);
        foreach ($diffs as $diff) {
            Storage::disk('s3')->deleteDirectory($diff);
        }
        dd('These are deleted out of s3:', $diffs);
    }
}
