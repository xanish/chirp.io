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
        # code...
    }

    public function following()
    {
        # code...
    }
}
