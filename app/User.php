<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'city', 'country', 'birthdate', 'profile_image',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getUserId($username)
    {
        $data = User::where('username', $username)->get();
        return $data[0]->id;
    }

    public function getUserByUsername($username)
    {
        $data = User::where('username', $username)
                    ->select('id', 'name', 'username', 'birthdate', 'city', 'country', 'created_at', 'profile_image')
                    ->get();
        return $data[0];
    }
}
