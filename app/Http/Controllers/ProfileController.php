<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $append = 'true';
        return view('home', compact('user', 'append'));
    }
}
