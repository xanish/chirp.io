<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\ServiceObjects\FollowerServiceObject;

class FollowersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $header = "Followers";
    $append = false;
    $user = Auth::user();
    try {
      $response = (new FollowerServiceObject)->getFollowersList($user);
    } catch (Exception $e) {
      return response()->json([
        'ERR' => $e->getMessage()
      ]);
    }

    return view('follows', compact('header', 'append', 'user', 'response'));
  }

  public function show($username)
  {
    $header = "Followers";
    $append = true;
    try {
      $user = (new User)->getUserByUsername($username);
      $response = (new FollowerServiceObject)->getFollowersList($user);
    } catch (Exception $e) {
      return response()->json([
        'ERR' => $e->getMessage()
      ]);
    }
    return view('follows', compact('header', 'append', 'user', 'response'));
  }
}
