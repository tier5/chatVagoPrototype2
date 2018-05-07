<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Model\FacebookUserPageDetail;
use App\Model\FacebookBoardcastUserInfo;
use App\Model\PageAccessToken;

class BoardcastController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public $pageScopeUserId = '';
	public $page_access_token = '';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {

    	$getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

        if($getPageAccessToken){

			$page_access_token = $getPageAccessToken['page_access_token'];
		}
		
        if(Auth::user()->id != ''){
			
			$getBoardCastUser = $getBroadcastDetail = FacebookBoardcastUserInfo::where('user_id', Auth::user()->id)->get();
		
		} else {

        	$getBoardCastUser = '';
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
        	CURLOPT_URL => "https://graph.facebook.com/".env("FB_APP_GRAPH_VERSION")."/me?fields=id,name&access_token=".$page_access_token."",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            ),
            ));
        $response_scope = curl_exec($curl);
        $err6 = curl_error($curl);
        curl_close($curl);
        if ($err6) {
			Log::info("Error Info : " . $err6);
        } else {
			
        	$page_scope = json_decode($response_scope, true);

	        	if(array_key_exists('name', $page_scope)) {

	        		$pageScopeUserId = $page_scope['id'];

	        	} else {

	        		$pageScopeUserId = '';
	        	}
	        }

	        return view('boardcast.list', compact(
            		'fb_page_id', '', 
            		'getBoardCastUser', $getBoardCastUser,
            		'pageScopeUserId',$pageScopeUserId,
                	'page_access_token', $page_access_token));
    }

	public function boadcast(Request $request) {

    	$getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

    	if($getPageAccessToken){

    		$page_access_token = $getPageAccessToken['page_access_token'];

    	}

    	$gerResult=[];

    	$getResult = explode(",", trim($request->get('selectedUser')));

    	$getMessage = trim($request->get('getMessage'));

    	$getBroadcastDetail = FacebookBoardcastUserInfo::where('user_id', Auth::user()->id)->get();

    	foreach ($getResult as $userInfo){

    		$jsonData[]['recipient']['id'] = $userInfo;
    		$jsonData[]['message']['text'] = $getMessage;
    	}

    	$url = 'https://graph.facebook.com/'.env("FB_APP_GRAPH_VERSION").'/me/broadcast_messages?access_token='.$page_access_token;

    	$ch = curl_init($url);

    	$jsonDataEncoded = json_encode($jsonData);

    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        $profile_error = curl_error($ch);
		curl_close($ch);
	}

	public function deleteUserRecords(Request $request) {

        $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

        if($getPageAccessToken){

        	$page_access_token = $getPageAccessToken['page_access_token'];

        }

        $getDeleteResult = [];

        $getDeleteResult = explode(",", trim($request->get('selectedUser')));

        if($request->get('allRecord') == 'none') {

        	foreach ($getDeleteResult as $userInfo){

        		$getBroadcastuser = FacebookBoardcastUserInfo::where('psid', $userInfo)->delete();
        	}
        	
        } else {

        	FacebookBoardcastUserInfo::truncate();
        }   
    }

}
