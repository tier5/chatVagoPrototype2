<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FacebookBoardcastUserInfo extends Model
{
   public $timestamps = true;
  protected $table = 'fb_boardcast_user_info';
  protected $fillable = [
        'first_name', 'last_name', 'profile_picture', 'fb_id', 'psid'
    ];

}
