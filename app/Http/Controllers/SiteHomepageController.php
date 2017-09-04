<?php

namespace App\Http\Controllers;

use App\ServiceObjects\WelcomePageServiceObject;
use Illuminate\Support\Facades\Auth;

class SiteHomePageController extends Controller
{
    private $welcomeSO;

    public function __construct(WelcomePageServiceObject $welcomeSO)
    {
        $this->welcomeSO = $welcomeSO;
    }

    public function index()
    {
        if (Auth::guest()) {
            $data = $this->welcomeSO->welcomePageData();
            $popular_tags = $data['popular_tags'];
            $tweets = $data['latest_tweets'];
            return view('welcome', compact('popular_tags', 'tweets'));
        }
        return redirect(action('HomeController@index'));
    }
}
