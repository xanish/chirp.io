<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public function users($id)
    {
        return $this->where('user_id', $id)->orWhere('follows', $id)->select('user_id', 'follows')->get();
    }
}
