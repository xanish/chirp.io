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
            $data = User::where('name', 'LIKE', '%'.$criteria.'%')
                ->orWhere('username', 'LIKE', '%'.$criteria.'%')
                ->select('id', 'name', 'username', 'birthdate', 'city', 'country', 'created_at', 'profile_image', 'profile_banner')
                ->orderBy('name')
                ->get();
        } catch (Exception $e) {
            throw new Exception("Unable To Get Search Results From DB");
        }
        return $data;
    }

    public function getSearchResultsForPage($criteria)
    {
        $ids = [];
        try {
            $data = $this->getSearchResults($criteria);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        if (Auth::user()) {
            $user = Auth::user();
            $ids = $user->following()->pluck('follows')->toArray();
        }
        return array('data' => $data, 'ids' => $ids);
    }
}
