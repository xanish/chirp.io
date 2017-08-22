<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use App\Utils\Utils;
use Auth;
use \Config;
use Carbon\Carbon;
use App\Formatter;

class TweetController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $tweet = new Tweet;
        $avatar = Config::get("constants.avatars");
        $text = $request->tweet_text;
        $image_name = null;
        if ($request->tweet_image){
            $image_name = (new Utils)->fitAndSaveImage($user->id, $request->tweet_image, Config::get('constants.tweet_image_width'), Config::get('constants.tweet_image_height'), 'tweet_images', 'scale-down');
        }
        $tweet->createTweet($user->id, $text, $image_name);
        $response = (new Formatter)->changeFormatToTweet($user, $text, $image_name);
        return response()->json([
          'element' => $response
        ]);
    }
}
