<?php
namespace App\ServiceObjects;

use Auth;
use \Config;
use App\User;

class UpdateProfileServiceObject
{
  public function saveProfile($id, $request, $image_name)
  {
    try {
      $entry = (new User)->updateUserDetails($id, $request, $image_name);
    } catch (Exception $e) {
      throw new Exception("Unable To Update Profile Details");
    }
  }

  public function updateProfile($request)
  {
    try {
      UpdateProfileServiceObject::saveProfile(Auth::user()->id, $request, UpdateProfileServiceObject::updateImage(Auth::user()->profile_image, $request->profile_image));
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function updateImage($profile_image, $new_image)
  {
    $image_name = $profile_image;
    if ($new_image) {
      try {
        $image_name = (new Utils)->fitAndSaveImage($user->id, $request->profile_image, Config::get('constants.avatar_width'), Config::get('constants.avatar_height'), 'avatars', 'fit');
      } catch (Exception $e) {
        throw new Exception("Unable To Update Profile Image");
      }
    }
    return $image_name;
  }
}
