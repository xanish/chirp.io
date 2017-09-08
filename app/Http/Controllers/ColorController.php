<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class ColorController extends Controller
{
    public static function getColor()
    {
        $color = "";
        if (Auth::user()) {
            $color = Auth::user()->accentColor()->firstOrFail();
            $color = $color->color;
        }
        return $color;
    }
}
