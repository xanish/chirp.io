<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    public function follow($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $id = Auth::id();
        $me = User::find($id);
        $me->following()->attach($user->id);
        return redirect()->back();
//        return response()->json([
//            'response' => 200
//        ]);
    }

    public function unfollow($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $id = Auth::id();
        $me = User::find($id);
        $me->following()->detach($user->id);
        return redirect()->back();
//        return response()->json([
//            'response' => 200
//        ]);
    }
}
