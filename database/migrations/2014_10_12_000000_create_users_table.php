<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->string('username', 20)->unique();
            $table->string('email', 40)->unique();
            $table->string('city', 20)->nullable()->default(NULL);
            $table->string('country', 20)->nullable()->default(NULL);
            $table->date('birthdate')->nullable()->default(NULL);
            $table->string('profile_image')->default('placeholder.jpg');
            $table->string('profile_banner')->default('banner.jpg');
            $table->string('password');
            $table->string('email_token', 100);
            $table->rememberToken();
            $table->tinyInteger('verified', 2)->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
