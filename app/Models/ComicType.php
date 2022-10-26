<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComicType extends Base
{
    protected $table = 'comic_types';

    protected $fillable = [
        'comic_id',
        'type_id'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}
