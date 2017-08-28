<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SiteHomePageController extends Controller
{
    public function index()
    {
        if (Auth::guest()) {
            return view('welcome');
        }
        return redirect(action('HomeController@index'));
    }
}
