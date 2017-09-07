<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\UpdateProfileServiceObject;

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
        $color = Auth::user()->accentColor()->firstOrFail();
        $color = $color->color;
        $user = Auth::user();
        $success = "";
        return view('edit', compact('user', 'success', 'color'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->updateSO->updateProfile($request);
        $color = Auth::user()->accentColor()->firstOrFail();
        $color = $color->color;
        $user = Auth::user();
        $success = "Profile Updated Successfully";
        return view('edit', compact('user', 'success', 'color'));
    }
}
