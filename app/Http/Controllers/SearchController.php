<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceObjects\SearchServiceObject;

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
        $response = $this->searchSO->getSearchResultsForPage($search);
        return view('search')->with([
            'data' => $response['data'],
            'ids' => $response['ids'],
        ]);
    }

    public function tags($tag, Request $request)
    {
        $data = $this->searchSO->getTweetsByTag($tag);
        return view('tags')->with([
            'posts' => $data['posts'],
            'liked' => $data['liked'],
            'tag' => '#'.$tag,
        ]);
    }
}
