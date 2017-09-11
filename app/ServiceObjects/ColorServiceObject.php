<?php
namespace App\ServiceObjects;

use Auth;
use App\Color;

class ColorServiceObject
{
    public function getColor()
    {
        $color = "";
        if (Auth::check() == true) {
            $color = Auth::user()->accentColor()->select('color')->get();
            $color = $color[0]->color;
        }
        return $color;
    }
}
