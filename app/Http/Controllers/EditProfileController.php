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
        $user = Auth::user();
        $success = "";
        return view('edit', compact('user', 'success'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->updateSO->updateProfile($request);
        $user = Auth::user();
        $success = "Profile Update Successfully";
        return view('edit', compact('user', 'success'));
    }
}
