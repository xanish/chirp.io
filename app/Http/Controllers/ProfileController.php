<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use App\DateFormatter;
use Carbon\Carbon;

class ProfileController extends Controller
{
  public function index()
  {
      $tweet = new Tweet;
      $formatted_feeds = new DateFormatter;
      $feeds = $tweet->getTweets();
      $feeds = $formatted_feeds->formatDate($feeds);

      return view('profile', compact('feeds'));
  }
}
