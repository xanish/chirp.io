<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\UpdateProfileServiceObject;
// use App\User;

class EditProfileController extends Controller
{
    private $updateSO;

    public function __construct(UpdateProfileServiceObject $updateSO)
    {
        $this->middleware('auth');
        $this->updateSO = $updateSO;
    }

    public function index()
    {
        $user = Auth::user();
        $success = "";
        $color = (new \App\ServiceObjects\ColorServiceObject)->getColor();
        $colors = ['default', 'blue', 'deep-purple', 'pink', 'green', 'orange'];
        return view('edit', compact('user', 'success', 'colors', 'color'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $color = $this->updateSO->updateProfile($request);
        $user = $color['user'];
        $color = $color['color'];
        $success = "Profile Updated Successfully";
        $colors = ['default', 'blue', 'deep-purple', 'pink', 'green', 'orange'];
        return view('edit', compact('user', 'success', 'colors', 'color'));
    }
}
