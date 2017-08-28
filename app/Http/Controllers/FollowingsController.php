<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\ServiceObjects\FollowerServiceObject;

class FollowingsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $header = "Following";
    $append = false;
    $user = Auth::user();
    try {
      $response = (new FollowerServiceObject)->getFollowingsList($user);
    } catch (Exception $e) {
      return response()->json([
        'ERR' => $e->getMessage()
      ]);
    }
    return view('follows', compact('header', 'append', 'user', 'response'));
  }

  public function store(Request $request)
  {
    try {
      $response = (new FollowerServiceObject)->addNewFollower($request->following);
    } catch (Exception $e) {
      return response()->json([
        'ERR' => $e->getMessage()
      ]);
    }
    return redirect('following');
  }

  public function update($follower)
  {
    try {
      $response = (new FollowerServiceObject)->updateFollower($follower);
    } catch (Exception $e) {
      return response()->json([
        'ERR' => $e->getMessage()
      ]);
    }
    return redirect('/following');
  }

  public function show($username)
  {
    echo 'hi';
    exit();
    $header = "Following";
    $append = true;
    try {
      $user = (new User)->getUserByUsername($username);
      $response = (new FollowerServiceObject)->getFollowingsList($user);
    } catch (Exception $e) {
      return response()->json([
        'ERR' => $e->getMessage()
      ]);
    }
    return view('follows', compact('header', 'append', 'user', 'response'));
  }
}
