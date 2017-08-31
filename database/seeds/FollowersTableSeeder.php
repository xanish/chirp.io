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
    }
}
