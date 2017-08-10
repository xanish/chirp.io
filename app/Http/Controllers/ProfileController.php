<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index($username)
    {
        $user = Auth::user();
        $append = 'true';
        return view('home', compact('user', 'append'));
    }
}
