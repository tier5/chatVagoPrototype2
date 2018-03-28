<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FacebookUserDetail extends Model
{
  public $timestamps = true;
  protected $table = 'fb_user_info';
}
