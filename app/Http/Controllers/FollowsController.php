<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\FollowerServiceObject;

class FollowsController extends Controller
{
    private $followerSO;

    public function __construct(FollowerServiceObject $followerSO)
    {
        $this->middleware('auth');
        $this->followerSO = $followerSO;
    }

    public function follow($username)
    {
        $this->followerSO->follow($username);
        return redirect()->back();
    }

    public function unfollow($username)
    {
        $this->followerSO->unfollow($username);
        return redirect()->back();
    }
}
