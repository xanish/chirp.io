<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Auth;
use App\User;
use Carbon\Carbon;

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
        if ($request->profile_image){
            $image_name = $user->id.'_'.time().'.'.$request->profile_image->getClientOriginalExtension();
            $request->profile_image->move(public_path('avatars'), $image_name);
        }
        $entry = User::where('id', Auth::user()->id)
            ->update([
                'name' => $request->name,
                'city' => $request->city,
                'country' => $request->country,
                'birthdate' => $request->birthdate,
                'profile_image' => $image_name,
                'updated_at' => Carbon::now(),
            ]);
        return redirect('/edit-profile');
    }
}
