<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comic extends Base
{
    public const NEW_COMIC_URL = 'https://truyenfull.vn/danh-sach/truyen-moi/';
    public const MAIN_URL = 'https://truyenfull.vn/';


    protected $fillable = [
        'name', 'comic_id', 'thumbnail_id', 'banner_id', 'description', 'status', 'count_chap', 'author_id',
    ];

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'comic_types', 'comic_id', 'type_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function thumbnail()
    {
        return $this->belongsTo(Image::class, 'thumbnail_id', 'id');
    }

    public function banner()
    {
        return $this->belongsTo(Image::class, 'banner_id', 'id');
    }

}
