<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $data = User::where('username', Auth::user()->username)->firstOrFail();
    return view('home', compact('data'));
  }
}
