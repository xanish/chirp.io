<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'email_token'
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
        return $this->hasMany(Tweet::class)->orderBy('id', 'DESC');
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
            if ($person->id == $id and $person->pivot->created_at == $person->pivot->updated_at)
            {
                return true;
            }
        }
        return false;
    }

    public function updateUserDetails($id, $request, $profile_image, $profile_banner, $time)
    {
        $this->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'city' => $request->city,
            'country' => $request->country,
            'birthdate' => $request->birthdate,
            'profile_image' => $profile_image,
            'profile_banner' => $profile_banner,
            'updated_at' => $time,
        ]);
    }

    public function findUsers($criteria)
    {
        return $this->where('name', 'LIKE', '%'.$criteria.'%')
            ->orWhere('username', 'LIKE', '%'.$criteria.'%')
            ->select('id', 'name', 'username', 'birthdate', 'city', 'country', 'created_at', 'profile_image', 'profile_banner')
            ->orderBy('name')
            ->limit(10);
    }
}
