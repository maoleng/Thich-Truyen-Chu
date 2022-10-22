<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
