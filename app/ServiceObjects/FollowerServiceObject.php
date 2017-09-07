<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use App\Follower;
use Carbon\Carbon;
use Exception;

class FollowerServiceObject {
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function follow ($uid)
    {
        try {
            $follow = $this->user->find($uid);
            $id = Auth::id();
            $user = $this->user->find($id);
            $follows = Follower::where([['user_id', $user->id],['follows', $follow->id]])->get();
            $now = Carbon::now();
            if (count($follows) > 0) {
                Follower::where([['user_id', $user->id],['follows', $follow->id]])->update([
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            else {
                $user->following()->attach($follow->id);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return response()->json(200);
    }

    public function unfollow ($uid)
    {
        try {
            $unfollow = $this->user->find($uid);
            $id = Auth::id();
            $user = $this->user->find($id);
            // $user->following()->detach($unfollow->id);
            Follower::where([['user_id', $user->id],['follows', $unfollow->id]])->update([
                'updated_at' => Carbon::now(),
            ]);
        } catch (Exception $e) {
            throw new Exception("Unable To Remove Follower");
        }
        return response()->json(200);
    }
}
