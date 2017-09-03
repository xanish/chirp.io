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

    public function follow($id)
    {
        return $this->followerSO->follow($id);
    }

    public function unfollow($id)
    {
        return $this->followerSO->unfollow($id);
    }
}
