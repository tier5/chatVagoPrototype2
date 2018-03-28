<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbBoardcastUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_boardcast_user_info', function (Blueprint $table) {
        $table->increments('id');
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->longText('profile_picture')->nullable();
        $table->string('fb_id')->nullable();
        $table->text('psid')->nullable();
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
        Schema::dropIfExists('fb_boardcast_user_info');
    }
}
