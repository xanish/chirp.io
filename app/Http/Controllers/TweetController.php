<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostTweetRequest;
use App\Tweet;
use App\Utils\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class TweetController extends Controller
{
    public function create(PostTweetRequest $request)
    {
        $id = Auth::id();
        $user = Auth::user();
        $image_name = null;
        if ($request->tweet_image) {
            $image_name = (new Utils)->fitAndSaveImage($id, $request->tweet_image, Config::get('constants.tweet_image_width'), Config::get('constants.tweet_image_height'), 'tweet_images', 'scale-down');
        }
        $tweet = Tweet::create([
            'user_id' => Auth::id(),
            'text' => $request->tweet_text,
            'tweet_image' => $image_name,
        ]);
        return response()->json([
          'element' => [
            'text' => $request->tweet_text,
            'image' => Config::get("constants.tweet_images").$image_name,
            'date' => Carbon::now()->diffForHumans(),
            'name' => $user->name,
            'username' => $user->username,
            'avatar' => Config::get("constants.avatars").$user->profile_image,
          ]
        ]);
    }
}
