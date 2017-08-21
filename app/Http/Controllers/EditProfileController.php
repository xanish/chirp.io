<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Auth;
use App\User;
use App\Utils\Utils;

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

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $image_name = $user->profile_image;
        if ($request->profile_image) {
            $image_name = (new Utils)->fitAndSaveImage($user->id, $request->profile_image, 300, 300, 'avatars', 'fit');
        }
        $entry = (new User)->updateUserDetails($user->id, $request, $image_name);
        return redirect('/edit-profile');
    }
}
