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
            return view('welcome');
        }
        return redirect(action('HomeController@index'));
    }

    public function getlatesttweets()
    {
        $data = $this->welcomeSO->welcomePageData();
        $tweets = $data['latest_tweets'];
        return response($tweets);
    }
}
