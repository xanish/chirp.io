<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'user_id', 'color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
