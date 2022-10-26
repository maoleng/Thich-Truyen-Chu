<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Base
{
    use HasFactory;

    protected $fillable = [
        'chap', 'name', 'content_url', 'comic_id',
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::create($date)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::create($date)->format('Y-m-d H:i:s');
    }
}
