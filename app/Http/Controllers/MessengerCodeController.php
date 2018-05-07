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

        if($getPageAccessToken){

            $page_access_token = $getPageAccessToken['page_access_token'];

        } else {

            $page_access_token = '';
        }

		return view('messengerCode.show', compact(
            		'fb_page_id', '',
                	'page_access_token', $page_access_token));
    }


    public function getMessengerImage(Request $request) {


        $messageImageData = '';
        
        $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

        if($getPageAccessToken){

            $page_access_token = $getPageAccessToken['page_access_token'];

        } else {

            $page_access_token = '';
        }

        $messengerRef = trim($request->get('messenger_ref'));
        $getImageSize = trim($request->get('image_size'));

        if($getImageSize == '') {

            $getImageSize = 1000;
        }

        $url = 'https://graph.facebook.com/'.env("FB_APP_GRAPH_VERSION").'/me/messenger_codes?access_token='.$page_access_token;
        $ch = curl_init($url);
            $jsonData = '{
                "type": "standard",
                "data": {
                "ref":"'.$messengerRef.'"
                },
                "image_size": "'.$getImageSize.'"
            }';
        $jsonDataEncoded = $jsonData;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $codeResult = curl_exec($ch);
        $profile_error = curl_error($ch);
        curl_close($ch);

        $messageImageData = $codeResult;

        return $messageImageData;

    }
}
