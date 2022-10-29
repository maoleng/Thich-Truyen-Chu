<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetError extends Command
{
    protected $signature = 'command:name';
    protected $description = 'Command description';

    public function handle()
    {
        $err = Temp::query()->whereNotIn('message', [
            'Undefined array key 0 at line: 91, at file: E:\laragon\www\Thich-Truyen-Chu\app\Http\Controllers\ComicController.php',
            'Undefined array key 0 at line: 89, at file: E:\laragon\www\Thich-Truyen-Chu\app\Http\Controllers\ComicController.php', //loi unicode
            'Undefined array key 0 at line: 86, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php',
            'Undefined array key 0 at line: 89, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', // loi unicode
            'Undefined array key 0 at line: 199, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', // loi unicode
            'Undefined array key 0 at line: 210, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', // loi unicode
            'Undefined array key 0 at line: 91, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php',
            'Undefined array key 0 at line: 195, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php',
            'Undefined array key 0 at line: 203, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php',
            'Undefined array key 0 at line: 209, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', // comic doesn't have any chapters
            'Undefined array key 0 at line: 213, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', // comic doesn't have any chapters
            'Undefined array key 0 at line: 224, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', // comic doesn't have any chapters
            'Undefined array key 0 at line: 224, at file: E:\laragon\www\Thich-Truyen-Chu\app\Http\Controllers\ComicController.php', // comic doesn't have any chapters
            'Undefined array key 0 at line: 213, at file: E:\laragon\www\Thich-Truyen-Chu\app\Http\Controllers\ComicController.php', // comic doesn't have any chapters
            'Undefined array key 0 at line: 196, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', // loi ko co tac gia
            'SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column \'chap\' at row 1 (SQL: insert into `chapters` (`chap`, `comic_id`, `name`, `content_url`, `id`, `updated_at`, `created_at`) values (bac-huyet-te-ma-ton-nang-vi-cai-gi-khong-vui/chuong-50, 821649d5-bb7b-4b3d-b2c3-847c43b317e7, Chương 50: 50: Lộ Tẩy, Comics/821649d5-bb7b-4b3d-b2c3-847c43b317e7/chap-bac-huyet-te-ma-ton-nang-vi-cai-gi-khong-vui/chuong-50.txt, 540fed13-17e4-4506-b37e-e28b1271ab3e, 2022-10-22 22:23:11, 2022-10-22 22:23:11)) at line: 760, at file: /var/www/Thich-Truyen-Chu/vendor/laravel/framework/src/Illuminate/Database/Connection.php',
            'SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column \'chap\' at row 1 (SQL: insert into `chapters` (`chap`, `comic_id`, `name`, `content_url`, `id`, `updated_at`, `created_at`) values (mon-lai-bi-thien-de-buc-hon-nua-roi/chuong-1, c782b861-6a79-4671-8503-45c933593f35, Chương 1: Thiên Đế bức hôn, Comics/c782b861-6a79-4671-8503-45c933593f35/chap-mon-lai-bi-thien-de-buc-hon-nua-roi/chuong-1.txt, 9969651b-ab9c-48b0-97af-adb2cd710b57, 2022-10-28 09:43:17, 2022-10-28 09:43:17)) at line: 760, at file: /var/www/Thich-Truyen-Chu/vendor/laravel/framework/src/Illuminate/Database/Connection.php',
            'Undefined array key 0 at line: 207, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', //khong loi
            'imagecreatefromjpeg(): gd-jpeg: JPEG library reports unrecoverable error: Empty input file at line: 233, at file: /var/www/Thich-Truyen-Chu/app/Http/Controllers/ComicController.php', //loi ko ton tai banner
            'imagecreatefromjpeg(): gd-jpeg: JPEG library reports unrecoverable error: Empty input file at line: 244, at file: E:\laragon\www\Thich-Truyen-Chu\app\Http\Controllers\ComicController.php', //loi ko ton tai banner
        ])
            ->whereNotIn('id', [
                10843, //loi banner nhung da tu het loi
            ])
            ->whereNotIn('status', [0, 1])
            ->whereNot('message', 'LIKE', '%Server error%')
            ->whereNot('message', 'LIKE', '%Client error%')
            ->get()->toArray();
        dd($err);
    }
}
