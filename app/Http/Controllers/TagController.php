<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hashtag;
use App\ServiceObjects\SearchServiceObject;
use App\Utils\Utils;
class TagController extends Controller
{
    private $searchSO;
    private $utils;

    public function __construct(SearchServiceObject $searchSO, Utils $utils)
    {
        $this->searchSO = $searchSO;
        $this->utils = $utils;
    }

    public function index($tag)
    {
        return view('tags')->with([
            'page' => 'tweets',
            'tag' => '#'.$tag,
        ]);
    }

    public function tags($tag)
    {
        $tags = Hashtag::where('tag', 'LIKE', '%'.$tag.'%')->groupBy('tag')->select('tag')->paginate(50);
        $page = 'tags';
        return view('tags', compact('tags', 'page', 'tag'));
    }

    public function tweets(Request $request)
    {
        $data = $this->searchSO->getTweetsByTag($request->tag, $request->lastid);
        return response($data);
    }

    public function popular_tags()
    {
        return $this->searchSO->popular();
    }
}
