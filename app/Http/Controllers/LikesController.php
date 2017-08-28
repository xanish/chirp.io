<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\LikeServiceObject;

class LikesController extends Controller
{
    private $likeSO;

    public function __construct(LikeServiceObject $likeSO)
    {
        $this->middleware('auth');
        $this->likeSO = $likeSO;
    }

    public function like($tweet_id)
    {
        $this->likeSO->like($tweet_id);
        return redirect()->back();
    }

    public function unlike($tweet_id)
    {
        $this->likeSO->unlike($tweet_id);
        return redirect()->back();
    }
}
