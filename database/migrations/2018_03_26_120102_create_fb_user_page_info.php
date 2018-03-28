<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbUserPageInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_user_page_info', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->nullable();
        $table->longText('fb_user_id')->nullable();
        $table->longText('fb_page_id')->nullable();
        $table->longText('fb_page_name')->nullable();
        $table->text('fb_page_picture')->nullable();
        $table->text('page_access_token')->nullable();
        $table->string('status')->nullable();
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
        Schema::dropIfExists('fb_user_page_info');
    }
}
