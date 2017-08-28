<?php

use App\Tweet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Danish
 * Date: 8/26/2017
 * Time: 8:49 PM
 */

class TweetsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tweets')->delete();
        for ($i = 1; $i < 6; $i++)
        {
            Tweet::create(array(
                'text' => str_random(10),
                'user_id' => $i
            ));
            Tweet::create(array(
                'text' => str_random(10),
                'tweet_image' => 'tweet.jpg',
                'user_id' => $i
            ));
            Tweet::create(array(
                'text' => str_random(10),
                'user_id' => $i
            ));
            Tweet::create(array(
                'text' => str_random(10),
                'user_id' => $i
            ));
        }
    }
}