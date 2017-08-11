<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Follower;

class ProfileController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $append = 'true';
        $showFollows = '';

        if (Auth::guest()) {

        }
        else {
            if ($user->username != Auth::user()->username) {
                $follows = Follower::where('following', $user->id)->where(function ($query) {
                    $query->whereColumn('updated_at', 'created_at');
                })->get();
                $follower_count = count($follows);
                $following = Follower::where('user_id', $user->id)->where(function ($query) {
                    $query->whereColumn('updated_at', 'created_at');
                })->get();
                $following_count = count($following);
                if (count($follows) == 0) {
                    $showFollows = 'false';
                }
                else if (count($follows) > 0) {
                    $showFollows = 'true';
                }
            }
        }
        // else {}
        return view('home', compact('user', 'append', 'showFollows', 'follower_count', 'following_count'));
    }
}
