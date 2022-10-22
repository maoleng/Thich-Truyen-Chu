<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temp extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'link', 'banner', 'count', 'status', 'message'
    ];
}
