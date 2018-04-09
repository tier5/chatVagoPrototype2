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
    public function index(Request $request) {

        $fb_page_id = '';
        $oneWeekTime ='';
        $logged_userId = '';
        $pageScopeUserId = '';

        $page_access_token = trim($request->get('page_access_token'));
        
        $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();

        if(count($getPageAccessToken) > 0 ){

            $page_access_token = $getPageAccessToken['page_access_token'];
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $page_access_token = trim($request->get('page_access_token'));

            if(count($getPageAccessToken) == 0 ){

                DB::table('page_access_token')->insert(['page_access_token' => $page_access_token, 'user_id' => Auth::user()->id]);

            } else {

                DB::table('page_access_token')
                ->where('id', $getPageAccessToken['id'])
                ->where('user_id', Auth::user()->id)
                ->update(['page_access_token' => $page_access_token]);
            }


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
                'page_access_token', $page_access_token,
                'logged_userId', $logged_userId,
                'pageScopeUserId', $pageScopeUserId
            ));

            } else {
                
            return view('home', compact(
            'fb_page_id', '',
            'page_access_token', $page_access_token,
            'pageScopeUserId', $pageScopeUserId,
            'logged_userId', $logged_userId));   
        } 
    }
}