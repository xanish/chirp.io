<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    // $this->middleware('auth');
  }

  /**
  * Show the users homepage.
  *
  * @return \Illuminate\Http\Response
  */
  public function index($username)
  {
    $data = User::where('username', $username)->firstOrFail();
    return view('home', compact('data'));
  }
}
