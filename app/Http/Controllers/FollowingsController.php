<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Follower;

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
        $showFollows = 'Own Profile';
        $data = (new Follower)->getPeopleFollowedByUser($user->username);
        $following_count = count($data);
        $follower_count = (new Follower)->getFollowersCount($user->username);
        return view('follows', compact('header', 'append', 'user', 'data', 'showFollows', 'follower_count', 'following_count'));
    }

    public function store(Request $request)
    {
        $data = (new User)->getUserId($request->following);
        $follower = new Follower;
        $follower->user_id = Auth::user()->id;
        $follower->following = $data;
        $follower->save();
        return redirect('following');
    }

    public function update($follower)
    {
        $data = (new User)->getUserId($follower);
        $entry = Follower::where('user_id', Auth::user()->id)
            ->where(function ($query) use ($data) {
                $query->where('following', $data);
            })->update(['updated_at' => Carbon::now()]);
        return redirect('/following');
    }
}
