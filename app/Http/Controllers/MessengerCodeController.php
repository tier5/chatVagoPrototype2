<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Model\FacebookUserPageDetail;
use App\Model\FacebookBoardcastUserInfo;
use App\Model\PageAccessToken;

class MessengerCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {

        $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

        if(count($getPageAccessToken) > 0 ){

            $page_access_token = $getPageAccessToken['page_access_token'];

        } else {

            $page_access_token = '';
        }

		return view('messengerCode.show', compact(
            		'fb_page_id', '',
                	'page_access_token', $page_access_token));
    }
}
