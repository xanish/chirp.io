<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use \Config;
use App\Formatter;
use Auth;
use App\Follower;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $data = [];
    $follows = [];
    $ids = [];
    if ($request->has('q')) {
      $data = (new User)->getUsers($request->q);
    }
    if (Auth::user()) {
      $follows = (new Follower)->getFollowings(Auth::user()->id);

      foreach ($follows as $f) {
        array_push($ids, $f->id);
      }
    }
    return view('search', compact('data', 'ids'));
  }

  public function show($value)
  {
    $results = (new User)->getUsers($value);
    $data = (new Formatter)->changeFormatToSearchItem($results);
    return $data;
  }
}
