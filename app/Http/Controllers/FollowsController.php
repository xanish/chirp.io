<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function followers()
    {
        return view('follows');
    }

    public function following()
    {
        return view('follows');
    }

    public function followersForUser($username)
    {
        return view('follows');
    }

    public function followingForUser($username)
    {
        return view('follows');
    }
}
