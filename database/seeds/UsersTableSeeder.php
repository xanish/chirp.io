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
        User::create(array(
            'name' => 'Test',
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('pass@123')
        ));

        User::create(array(
            'name' => 'Test 2',
            'username' => 'test2',
            'email' => 'test2@test.com',
            'password' => bcrypt('pass@123')
        ));
        User::create(array(
            'name' => 'Test 3',
            'username' => 'test3',
            'email' => 'test3@test.com',
            'password' => bcrypt('pass@123')
        ));
    }
}