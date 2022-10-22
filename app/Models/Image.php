<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'source', 'size'
    ];

}
