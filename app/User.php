<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follows')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'follows');
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class)->orderBy('created_at', 'DESC');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function accentColor()
    {
        return $this->hasOne(Color::class);
    }

    public function liked($id)
    {
        foreach ($this->likes as $like)
        {
            if ($like->tweet_id == $id)
            {
                return true;
            }
        }
        return false;
    }

    public function follows($id)
    {
        foreach ($this->following as $person)
        {
            if ($person->id == $id)
            {
                return true;
            }
        }
        return false;
    }
}
