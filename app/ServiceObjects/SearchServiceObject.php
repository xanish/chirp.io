<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use App\Follower;

class SearchServiceObject
{
  public function getSearchResults($criteria)
  {
    try {
      $data = (new User)->getUsers($criteria);
    } catch (Exception $e) {
      throw new Exception("Unable To Get Search Results From DB");
    }
    return $data;
  }

  public function getSearchResultsForPage($criteria)
  {
    $ids = [];
    try {
      $data = SearchServiceObject::getSearchResults($criteria);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    if (Auth::user()) {
      try {
        $follows = (new Follower)->getFollowings(Auth::user()->id);
      }
      catch (Exception $e) {
        throw new Exception("Unable To Get Follower Details To Populate Search Results");
      }
      foreach ($follows as $f) {
        array_push($ids, $f->id);
      }
    }
    return array('data' => $data, 'ids' => $ids);
  }
}
