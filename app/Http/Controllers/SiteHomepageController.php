<?php

namespace App\Http\Controllers;

use App\ServiceObjects\WelcomePageServiceObject;
use Illuminate\Support\Facades\Auth;
use App\Utils\Utils;
class SiteHomePageController extends Controller
{
    private $welcomeSO;

    public function __construct(WelcomePageServiceObject $welcomeSO, Utils $utils)
    {
        $this->welcomeSO = $welcomeSO;
        $this->utils = $utils;
    }

    public function index()
    {
        if (Auth::guest()) {
            $data = $this->welcomeSO->welcomePageData();
            // $popular_tags = $data['popular_tags'];
            $tweets = $data['latest_tweets'];
            return view('welcome', compact('tweets'));
        }
        return redirect(action('HomeController@index'));
    }
}
