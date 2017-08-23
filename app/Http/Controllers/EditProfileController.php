<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Auth;
use App\User;
use App\Utils\Utils;
use \Config;

class EditProfileController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $user = Auth::user();
    return view('edit', compact('user'));
  }

  public function create(Request $request)
  {
    $user = Auth::user();
    $image_name = $user->profile_image;
    if ($request->profile_image) {
      $image_name = (new Utils)->fitAndSaveImage($user->id, $request->profile_image, Config::get('constants.avatar_width'), Config::get('constants.avatar_height'), 'avatars', 'fit');
    }
    $entry = (new User)->updateUserDetails($user->id, $request, $image_name);
    return redirect('/edit-profile');
  }
}
