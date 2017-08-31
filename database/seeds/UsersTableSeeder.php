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
        User::create(array(
            'name' => 'Danish Ali Furniturewala',
            'username' => 'danish',
            'email' => 'danish.f@media.net',
            'password' => bcrypt('pass@123')
        ));
        User::create(array(
            'name' => 'Akshay Varma',
            'username' => 'akshay',
            'email' => 'akshay.v@media.net',
            'password' => bcrypt('pass@123')
        ));
        factory(App\User::class, 50)->create()->each(function ($u) {
            $u->following()->attach(1);
            $u->following()->attach(2);
            for ($i=0; $i < 100; $i++) {
                $u->tweets()->save(factory(App\Tweet::class)->make());
            }
        });
    }
}
