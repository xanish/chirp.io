<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Danish
 * Date: 8/25/2017
 * Time: 6:31 PM
 */

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();
        factory(App\User::class, 50)->create()->each(function ($u) {
            for ($i=0; $i < 100; $i++) {
                $u->tweets()->save(factory(App\Tweet::class)->make());
            }
        });
    }
}
