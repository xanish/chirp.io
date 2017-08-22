<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use \Config;
use App\Formatter;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $data = [];
    if ($request->has('q')) {
      $data = (new User)->getUsers($request->q);
    }
    return view('search', compact('data'));
  }

  public function show($value)
  {
    $results = (new User)->getUsers($value);
    $data = (new Formatter)->changeFormatToSearchItem($results);
    return $data;
  }
}
