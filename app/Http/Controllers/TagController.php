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

    public function tags($tag)
    {
        $tags = Hashtag::where('tag', 'LIKE', '%'.$tag.'%')->groupBy('tag')->select('tag')->paginate(50);
        $page = 'tags';
        $color = $this->utils->getColor();
        return view('tags', compact('tags', 'page', 'color', 'tag'));
    }

    public function tweets($tag, Request $request)
    {
        $data = $this->searchSO->getTweetsByTag($tag);
        return view('tags')->with([
            'page' => 'tweets',
            'posts' => $data['posts'],
            'liked' => $data['liked'],
            'tag' => '#'.$tag,
            'tags' => $data['tags'],
            'color' => $this->utils->getColor(),
        ]);
    }

    public function popular_tags()
    {
        return $this->searchSO->popular();
    }
}
