<?php

use App\Follower;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Danish
 * Date: 8/26/2017
 * Time: 8:47 PM
 */

class FollowersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('followers')->delete();
        Follower::create(array(
            'user_id' => 1,
            'follows' => 2,
        ));
        Follower::create(array(
            'user_id' => 1,
            'follows' => 3,
        ));
        Follower::create(array(
            'user_id' => 1,
            'follows' => 4,
        ));
        Follower::create(array(
            'user_id' => 2,
            'follows' => 1,
        ));
        Follower::create(array(
            'user_id' => 5,
            'follows' => 1,
        ));
        Follower::create(array(
            'user_id' => 3,
            'follows' => 1,
        ));
        Follower::create(array(
            'user_id' => 3,
            'follows' => 2,
        ));
        Follower::create(array(
            'user_id' => 4,
            'follows' => 2,
        ));
    }
}