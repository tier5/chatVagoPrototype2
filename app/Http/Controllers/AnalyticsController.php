<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Model\FacebookUserPageDetail;
use App\Model\FacebookBoardcastUserInfo;
use App\Model\PageAccessToken;
class AnalyticsController extends Controller
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

        $fb_page_id = '';
        $oneWeekTime ='';
        $logged_userId = '';

        $page_messages_reported_conversations_by_report_type_unique = [];
        $page_messages_new_conversations_unique = [];
        $page_messages_open_conversations_unique = [];
        $page_messages_blocked_conversations_unique =[];
        $page_messages_active_threads_unique =[];
        $pageScopeUserId = '';

        $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

        if(count($getPageAccessToken) > 0 )
        	{
        		$page_access_token = $getPageAccessToken['page_access_token'];

        	} else {

        		$page_access_token = '';
        	}

        $todayTime = time();
  

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$oneWeekTime = trim($request->get('dateRange'));

			if($oneWeekTime == "7days") {

			$untilTime = strtotime("-1 week");

			} else if($oneWeekTime == "1month") {

			$untilTime = strtotime("-1 month");

			} else {

			$untilTime = strtotime("-1 day");
			}


    	} else {

    		$untilTime = strtotime("-1 day");
    	}
        
        // For page_messages_reported_conversations_by_report_type_unique
        $curl = curl_init();
        curl_setopt_array($curl, array(
        	CURLOPT_URL => "https://graph.facebook.com/v2.12/me/insights?access_token=".$page_access_token."&since=".$untilTime."&until=".$todayTime."&metric=page_messages_reported_conversations_by_report_type_unique",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_TIMEOUT => 30000,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                ),
        ));
        $response1 = curl_exec($curl);
        $err1 = curl_error($curl);
        curl_close($curl);
        if ($err1) {
        	echo "cURL Error #:" . $err1;
        } else {

        	$report_type_unique = json_decode($response1, true);
        	if(array_key_exists('data', $report_type_unique)) {

        		if(count($report_type_unique['data']) > 0) {

        			$page_messages_reported_conversations_by_report_type_unique = array_reverse($report_type_unique['data'][0]['values']);

        		} else {

        			$page_messages_reported_conversations_by_report_type_unique =[];
        		}
        	} else {

        		$page_messages_reported_conversations_by_report_type_unique =[];
        	}
        }

        // For page_messages_new_conversations_unique
        $curl = curl_init();
        curl_setopt_array($curl, array(
        	CURLOPT_URL => "https://graph.facebook.com/v2.12/me/insights?access_token=".$page_access_token."&since=".$untilTime."&until=".$todayTime."&metric=page_messages_new_conversations_unique",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_TIMEOUT => 30000,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => array(
        		'Content-Type: application/json',
        	),
        ));
        $response2 = curl_exec($curl);
        $err2 = curl_error($curl);
        curl_close($curl);
        if ($err2) {
        	echo "cURL Error #:" . $err2;
        } else {
        	$new_conversations_unique = json_decode($response2, true);
        	if(array_key_exists('data', $new_conversations_unique)) {
        		if(count($new_conversations_unique['data']) > 0) {
        			$page_messages_new_conversations_unique = array_reverse($new_conversations_unique['data'][0]['values']);
        		} else {
        			$page_messages_new_conversations_unique = [];
        		}
        	} else {
        		$page_messages_new_conversations_unique = [];
        	}
        }

        // For page_messages_open_conversations_unique
        $curl = curl_init();
        curl_setopt_array($curl, array(
        	CURLOPT_URL => "https://graph.facebook.com/v2.12/me/insights?access_token=".$page_access_token."&since=".$untilTime."&until=".$todayTime."&metric=page_messages_open_conversations_unique",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_TIMEOUT => 30000,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => array(
        		'Content-Type: application/json',
        	),
        ));
        $response3 = curl_exec($curl);
        $err3 = curl_error($curl);
        curl_close($curl);
        if ($err3) {
        	echo "cURL Error #:" . $err3;
        } else {
        	$open_conversations_unique = json_decode($response3, true);

        	if(array_key_exists('data', $open_conversations_unique)) {
        		if(count($open_conversations_unique['data']) > 0) {

        			$page_messages_open_conversations_unique = array_reverse($open_conversations_unique['data'][0]['values']);

        		} else {

        			$page_messages_open_conversations_unique = [];
        		}
        	} else {

        		$page_messages_open_conversations_unique = [];
        	}
        }

        // For page_messages_blocked_conversations_unique
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        	CURLOPT_URL => "https://graph.facebook.com/v2.12/me/access_token/insights?access_token=".$page_access_token."&since=".$untilTime."&until=".$todayTime."&metric=page_messages_blocked_conversations_unique",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_TIMEOUT => 30000,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => array(
        		'Content-Type: application/json',
        	),
        ));

        $response4 = curl_exec($curl);
        $err4 = curl_error($curl);
        curl_close($curl);
        if ($err4) {
        	echo "cURL Error #:" . $err4;
        } else {

        	$blocked_conversations_unique = json_decode($response4, true);

        	if(array_key_exists('data', $blocked_conversations_unique)) {

        		if(count($blocked_conversations_unique['data']) > 0) {

        			$page_messages_blocked_conversations_unique = array_reverse($blocked_conversations_unique['data'][0]['values']);

        		}else{

        			$page_messages_blocked_conversations_unique = [];
        		}
        	} else {

        		$page_messages_blocked_conversations_unique = [];
        	}
        }

        // For page_messages_active_threads_unique
        
        $curl = curl_init();
        curl_setopt_array($curl, array(

        	CURLOPT_URL => "https://graph.facebook.com/v2.12/me/insights?access_token=".$page_access_token."&since=".$untilTime."&until=".$todayTime."&metric=page_messages_active_threads_unique",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_TIMEOUT => 30000,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => array(
        		'Content-Type: application/json',
        	),
        ));

        $response5 = curl_exec($curl);
        $err5 = curl_error($curl);
        curl_close($curl);
        if ($err5) {
        	echo "cURL Error #:" . $err5;
        } else {
        	$active_threads_unique = json_decode($response5, true);

        	if(array_key_exists('data', $active_threads_unique)) {

        		if(count($active_threads_unique['data']) > 0) {

        			$page_messages_active_threads_unique = array_reverse($active_threads_unique['data'][0]['values']);

        		} else {

        			$page_messages_active_threads_unique = [];
        		}
        	} else {

        		$page_messages_active_threads_unique = [];
        	}
        }

        return view('analytics.list', compact('fb_page_id', $fb_page_id,
        	'page_messages_reported_conversations_by_report_type_unique',
        	$page_messages_reported_conversations_by_report_type_unique,
        	'page_messages_new_conversations_unique', $page_messages_new_conversations_unique,
        	'page_messages_open_conversations_unique', $page_messages_open_conversations_unique,
        	'page_messages_blocked_conversations_unique', $page_messages_blocked_conversations_unique,
        	'page_messages_active_threads_unique', $page_messages_active_threads_unique,
        	'oneWeekTime', $oneWeekTime,
        	'page_access_token', $page_access_token,
        	'pageScopeUserId', $pageScopeUserId
        ));
    }
}
