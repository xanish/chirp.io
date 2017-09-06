<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceObjects\SearchServiceObject;
use Auth;

class SearchController extends Controller
{
    private $searchSO;

    public function __construct(SearchServiceObject $searchSO)
    {
        $this->searchSO = $searchSO;
    }

    public function search(Request $request)
    {
        return $this->searchSO->getSearchResults($request->q);
    }

    public function results($search)
    {
        $color = "";
        $response = $this->searchSO->getSearchResultsForPage($search);
        if (Auth::user()) {
            $color = Auth::user()->accentColor()->firstOrFail();
            $color = $color->color;
        }
        return view('search')->with([
            'data' => $response['data'],
            'ids' => $response['ids'],
            'color' => $color,
        ]);
    }

    public function tags($tag, Request $request)
    {
        $color = "";
        $data = $this->searchSO->getTweetsByTag($tag);
        if (Auth::user()) {
            $color = Auth::user()->accentColor()->firstOrFail();
            $color = $color->color;
        }
        return view('tags')->with([
            'posts' => $data['posts'],
            'liked' => $data['liked'],
            'tag' => '#'.$tag,
            'color' => $color,
        ]);
    }
}
