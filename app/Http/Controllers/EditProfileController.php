<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\UpdateProfileServiceObject;
use App\Utils\Utils;

class EditProfileController extends Controller
{
    private $updateSO;
    private $utils;

    public function __construct(UpdateProfileServiceObject $updateSO, Utils $utils)
    {
        $this->middleware('auth');
        $this->updateSO = $updateSO;
        $this->utils = $utils;
    }

    public function index()
    {
        $user = Auth::user();
        $success = "";
        $color = $this->utils->getColor();
        $colors = ['default', 'blue', 'deep-purple', 'pink', 'green', 'orange'];
        return view('edit', compact('user', 'success', 'color', 'colors'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->updateSO->updateProfile($request);
        $color = $this->utils->getColor();
        $user = Auth::user();
        $success = "Profile Updated Successfully";
        $colors = ['default', 'blue', 'deep-purple', 'pink', 'green', 'orange'];
        return view('edit', compact('user', 'success', 'color', 'colors'));
    }
}
