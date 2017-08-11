<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'city', 'country', 'birthdate'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getUserId($username)
    {
        $data = User::where('username', $username)->get();
        return $data[0]->id;
    }
}
