<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public function users($where)
    {
        return $this->whereRaw($where)->select('user_id', 'follows')->get();
    }
}
