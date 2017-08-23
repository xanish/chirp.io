<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
  use Notifiable;

  protected $fillable = [
    'name', 'username', 'email', 'password', 'city', 'country', 'birthdate', 'profile_image',
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];

  public function createNewUser($data)
  {
    return User::create([
      'name' => $data['name'],
      'username' => $data['username'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
      'city' => null,
      'country' => null,
      'birthdate' => null,
      'profile-image' => 'placeholder.jpg',
    ]);
  }

  public function updateUserDetails($userid, $data, $image_name)
  {
    return User::where('id', $userid)
    ->update([
      'name' => $data->name,
      'city' => $data->city,
      'country' => $data->country,
      'birthdate' => $data->birthdate,
      'profile_image' => $image_name,
      'updated_at' => Carbon::now(),
    ]);
  }

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

  public function getUsers($value)
  {
    $data = User::where('name', 'LIKE', '%'.$value.'%')
    ->orWhere('username', 'LIKE', '%'.$value.'%')
    ->select('id', 'name', 'username', 'birthdate', 'city', 'country', 'created_at', 'profile_image')
    ->get();
    return $data;
  }
}
