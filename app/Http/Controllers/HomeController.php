<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Model\FacebookUserPageDetail;
use App\Model\FacebookBoardcastUserInfo;
use App\Model\PageAccessToken;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //dd(Auth::user()->id);
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

        //$page_access_token = $this->page_access_token;

        //dd(Auth::user()->id);

        if(Auth::user()->id != '') {

            $getBroadcastDetail = FacebookBoardcastUserInfo::where('user_id', Auth::user()->id)->get();

        } else  {

            $getBroadcastDetail = [];

        }

        // For Facebook Broadcast

        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $todayTime = time();

            $oneWeekTime = trim($request->get('dateRange'));

            if($oneWeekTime == "7days") {

               $untilTime = strtotime("-1 week");

            } else if($oneWeekTime == "1month") {

                $untilTime = strtotime("-1 month");

            } else {

                $untilTime = strtotime("-1 day");
            }

            $fb_page_id = $request->get('fb_page_id');

            $page_access_token = trim($request->get('page_access_token'));


            $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();


            if(count($getPageAccessToken) == 0 ) 
            {

               //PageAccessToken::create(['page_access_token' => $page_access_token, 'user_id' => Auth::user()->id ]);

                DB::table('page_access_token')->insert(['page_access_token' => $page_access_token, 'user_id' => Auth::user()->id]);

            }
            else 

            {

                    DB::table('page_access_token')
                    ->where('id', $getPageAccessToken['id'])
                    ->where('user_id', Auth::user()->id)
                    ->update(['page_access_token' => $page_access_token]);



            }


            // $getPageDetail = FacebookUserPageDetail::where('fb_page_id', $fb_page_id)->first();

            // if(count($getPageDetail) > 0) {

                $curl = curl_init();

                // For page_messages_reported_conversations_by_report_type_unique
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
                CURLOPT_URL => "https://graph.facebook.com/v2.12/access_token/insights?access_token=".$page_access_token."&since=".$untilTime."&until=".$todayTime."&metric=page_messages_blocked_conversations_unique",
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


                // get Page scope user id 
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://graph.facebook.com/v2.12/me?fields=id,name&access_token=".$page_access_token."",
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
                echo "cURL Error #:" . $err6;
                } else {
                $page_scope = json_decode($response_scope, true);

                    if(array_key_exists('name', $page_scope)) {

                        $pageScopeUserId = $page_scope['id'];


                    } else {

                        $pageScopeUserId = '';
                    }
                    
                }

                return view('home', compact('fb_page_id', $fb_page_id,
                    'page_messages_reported_conversations_by_report_type_unique', 
                    $page_messages_reported_conversations_by_report_type_unique,
                    'page_messages_new_conversations_unique', $page_messages_new_conversations_unique,
                    'page_messages_open_conversations_unique', $page_messages_open_conversations_unique,
                    'page_messages_blocked_conversations_unique', $page_messages_blocked_conversations_unique,
                    'page_messages_active_threads_unique', $page_messages_active_threads_unique,
                    'oneWeekTime', $oneWeekTime,
                    'page_access_token', $page_access_token,
                    'getBroadcastDetail', $getBroadcastDetail,
                    'logged_userId', $logged_userId,
                    'pageScopeUserId', $pageScopeUserId

                ));

            



        } else{

             return view('home', compact(
                'fb_page_id', '', 
                'oneWeekTime', $oneWeekTime, 
                'page_access_token', $page_access_token,
                'pageScopeUserId', $pageScopeUserId,
                'logged_userId', $logged_userId,
                'getBroadcastDetail', $getBroadcastDetail));   
            } 
        }

        public function insertRecords(Request $request) {

            $psid = trim($request->get('psid'));

            $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

            if(count($getPageAccessToken) > 0 ) 
            {

                $page_access_token = $getPageAccessToken['page_access_token'];

            } else {

                $page_access_token = ''; 
            }


           $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://graph.facebook.com/v2.12/".$psid."?fields=first_name,last_name,profile_pic&access_token=".$page_access_token."",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                ),
                ));
                $profile_response = curl_exec($curl);
                $profile_error = curl_error($curl);
                curl_close($curl);
                if ($profile_error) {

                    return $profile_error;
                } else {
                    
                    $getUserProfileData = json_decode($profile_response);

                    if(array_key_exists('first_name', $getUserProfileData)) {

                    $getBroadcastuser = FacebookBoardcastUserInfo::where('fb_id', $getUserProfileData->id)->first();

                    if(count($getBroadcastuser) == 0) {

                        FacebookBoardcastUserInfo::create([
                            'first_name' => $getUserProfileData->first_name,
                            'last_name' =>  $getUserProfileData->last_name,
                            'profile_picture' => $getUserProfileData->profile_pic,
                            'fb_id' => $getUserProfileData->id,
                            'psid' => $psid
                        ]);
                    }
                    
                } else {

                    
                }
            }
    }


    public function boadcast(Request $request) {

            $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

            if(count($getPageAccessToken) > 0 ) 
            {

                $page_access_token = $getPageAccessToken['page_access_token'];

            } else {

                $page_access_token = ''; 
            }

           $gerResult=[];

           $getResult = explode(",", trim($request->get('chooseUser')));

            $getMessage = trim($request->get('getMesg'));

            $getBroadcastDetail = FacebookBoardcastUserInfo::where('user_id', Auth::user()->id)->get();

                foreach ($getResult as $userInfo)
                {
                    $this->sendMessageText($userInfo, $getMessage);
                    
                }         
            }

        function sendMessageText($userId, $getMessage) {

            $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

            if(count($getPageAccessToken) > 0 ) 
            {

                $page_access_token = $getPageAccessToken['page_access_token'];

            } else {

                $page_access_token = ''; 
            }

            $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$page_access_token;
                    $ch = curl_init($url);
                    $jsonData = '{
                    "recipient":{
                    "id":"'.$userId.'"
                    },
                    "message":{
                    "text":"'.$getMessage .'"
                    }
                    }';
                    $jsonDataEncoded = $jsonData;
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    $result = curl_exec($ch);
                    $profile_error = curl_error($ch);
                    curl_close($ch);
                if ($profile_error) {

                } else {

                }

        }

    public function deleteUserRecords(Request $request) {

        $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

        if(count($getPageAccessToken) > 0 ) 
        {

            $page_access_token = $getPageAccessToken['page_access_token'];

        } else {

            $page_access_token = ''; 
        }

           
        $getDeleteResult = [];

        $getDeleteResult = explode(",", trim($request->get('chooseUser')));


            if($request->get('allRecord') == 'none') {

                foreach ($getDeleteResult as $userInfo)
                {

                   $getBroadcastuser = FacebookBoardcastUserInfo::where('psid', $userInfo)->delete();

                }

            } else {

            FacebookBoardcastUserInfo::truncate();
        }   
    }

}