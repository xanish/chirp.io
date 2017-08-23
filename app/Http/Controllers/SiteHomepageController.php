<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SiteHomepageController extends Controller
{
  public function index()
  {
    if (Auth::guest()) {
      return view('welcome');
    }
    return redirect(action('HomeController@index'));
  }
}
