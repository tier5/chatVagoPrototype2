<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Model\FacebookUserPageDetail;
use App\Model\FacebookBoardcastUserInfo;
use App\Model\PageAccessToken;

class ApiController extends Controller
{
    public function index(Request $request) {

            $psid = trim($request->get('psid'));
           
            
           $getPageAccessToken = PageAccessToken::all();

            if(count($getPageAccessToken) == 0 ) 
            {

            $page_access_token = $getPageAccessToken[0]['page_access_token'];

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

                    echo  $profile_error;
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

                    echo '{
						"messages": [
						{ "text": " " }
						]
					}';
	
                    
                } else {

          			echo "Something went wrong!";
                    
                }
        }
    }
}
