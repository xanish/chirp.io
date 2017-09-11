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
        $colors = ['default', 'blue', 'deep-purple', 'pink', 'green', 'orange'];
        return view('edit', compact('user', 'success', 'colors'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $this->updateSO->updateProfile($request);
        $user = $data['user'];
        $success = "Profile Updated Successfully";
        $colors = ['default', 'blue', 'deep-purple', 'pink', 'green', 'orange'];
        return view('edit', compact('user', 'success', 'colors'));
    }
}
