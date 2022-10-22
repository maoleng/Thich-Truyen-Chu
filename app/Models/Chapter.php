<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Base
{
    use HasFactory;

    protected $fillable = [
        'chap', 'name', 'content_url', 'comic_id',
    ];
}
