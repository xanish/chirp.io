<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceObjects\SearchServiceObject;
use Auth;
use App\Utils\Utils;

class SearchController extends Controller
{
    private $searchSO;
    private $utils;

    public function __construct(SearchServiceObject $searchSO, Utils $utils)
    {
        $this->searchSO = $searchSO;
        $this->utils = $utils;
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
            'criteria' => $search,
        ]);
    }
}
