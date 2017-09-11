<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Populartag extends Model
{
    protected $fillable = [
        'tag', 'tag_count',
    ];

    public $timestamps = false;
}
