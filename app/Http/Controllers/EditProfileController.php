<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\User;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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
        $banner = $user->profile_banner;
        if ($request->profile_image) {
            $image_name = (new Utils)->fitAndSaveImage($user->id, $request->profile_image, Config::get('constants.avatar_width'), Config::get('constants.avatar_height'), 'avatars', 'fit');
        }
        if ($request->profile_banner) {
            $banner = $user->id.'_'.time().'.'.$request->profile_banner->getClientOriginalExtension();
            $request->profile_banner->move('banners/',$banner);
        }

        $entry = User::where('id', $user->id)->update([
            'name' => $request->name,
            'city' => $request->city,
            'country' => $request->country,
            'birthdate' => $request->birthdate,
            'profile_image' => $image_name,
            'profile_banner' => $banner,
            'updated_at' => Carbon::now(),
        ]);
        return redirect('/edit-profile');
    }
}
