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
        return view('edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->updateSO->updateProfile($request);
        return redirect('/edit-profile');
    }
}
