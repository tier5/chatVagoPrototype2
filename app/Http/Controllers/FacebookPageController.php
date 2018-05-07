<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View,Auth,Session,Redirect;
use App\Model\FacebookUserDetail;
use App\Model\FacebookUserPageDetail;
use App\Model\FbuserPageInfo;
use App\Model\PageAccessToken;
use DB;
use Facebook\Facebook as Facebook;
class FacebookPageController extends Controller
{

	public function saveFacebookUser(Request $request){
			$user_id = Auth::user()->id;
			foreach ($request->response as $key => $value) {
			$fb_user_id = json_decode($value['userID'],true);
			$fb_user_access = $value['accessToken'];
			$longLivedToken = $this->generateAccessToken($fb_user_access);
			$request->session()->put('fb_user_id', $fb_user_id);
			$request->session()->put('longLivedToken', $longLivedToken);

			$checkUser = FacebookUserDetail::where('fb_user_id',$fb_user_id)
						->where('user_id',$user_id)
						->first();

			if(count($checkUser)==0){
			$save_fb_user = new FacebookUserDetail;
			$save_fb_user->user_id = $user_id;
			$save_fb_user->fb_user_id = $fb_user_id;
			$save_fb_user->accessToken = $longLivedToken;
			$save_fb_user->status = 'connected';
			$save_fb_user->save();
			return 'user saved';
			}else{
			$checkUser->accessToken = $longLivedToken;
			$checkUser->save();
			return $fb_user_access;
			}
      	}
  	}


  	public function saveFacebookUserPageToken(Request $request) {
		  $page_access_token = trim($request->get('page_access_token'));
		  $page_id = trim($request->get('page_id'));
		  //$longLivedPageToken = $this->generatePageAccessToken($page_id, $page_access_token);
		  $getPageAccessToken = PageAccessToken::where('user_id', Auth::user()->id)->first();
		  if($getPageAccessToken) {
  			DB::table('page_access_token')
                ->where('id', $getPageAccessToken['id'])
                ->where('user_id', Auth::user()->id)
				->update(['page_access_token' => $page_access_token]);
			} else {
				DB::table('page_access_token')->insert(['page_access_token' => $page_access_token, 'user_id' => Auth::user()->id]);
				return 'Insert';
			}
		}
     
	public function getFacebookPageData (Request $request){
		$user_id = Auth::user()->id;
		$fb_user_id = $request->session()->get('fb_user_id');
		if($fb_user_id == '' || $request->session()->get('fb_user_id')!= $request['facebookID']) {
			$fb_user_id = $request['facebookID'];
			$fb_user_id = $request->session()->put('fb_user_id', $fb_user_id);
		}
		$getFBData = FacebookUserPageDetail::where('fb_user_id',$fb_user_id)->get();
		if(count($getFBData) > 0) {
			$allFBLoggedPageData = [];
			foreach ($getFBData as $key=> $allPageData){
				$allFBLoggedPageData[$key]['fb_user_id'] = $allPageData['fb_user_id'];
				$allFBLoggedPageData[$key]['fb_page_id'] = $allPageData['fb_page_id'];
				$allFBLoggedPageData[$key]['page_access_token'] = $allPageData['page_access_token'];
				$allFBLoggedPageData[$key]['fb_page_name'] = $allPageData['fb_page_name'];
			}
			return $allFBLoggedPageData;
		} else {
			return $allFBLoggedPageData;
		}	
	}
    public function saveFbUserPageData(Request $request){
		$user_id = Auth::user()->id;
		$fb_user_id = $request->session()->get('fb_user_id');
      	if($request->response['data']) {
	      foreach($request->response['data'] as $key =>$data){
	          $page_name =  $data['name'];
	          $page_id =  $data['id'];
	          $page_access_token = $data['access_token'];
	          $longLivedPageToken = $this->generatePageAccessToken($data['id']);
	          //$page_profile_picture = $this->getPageProfilePicture($data['id'], $page_access_token);
			  $checkPage = FacebookUserPageDetail::where('fb_page_id',$page_id)
													  ->where('user_id',$user_id)
													  ->first();
	          if(count($checkPage)==0){
	            $save_fb_page = new FacebookUserPageDetail;
	            $save_fb_page->user_id = $user_id;
	            $save_fb_page->fb_user_id = $fb_user_id;
	            $save_fb_page->fb_page_id = $page_id;
	            $save_fb_page->fb_page_name = $page_name;
	            //$save_fb_page->fb_page_picture = $page_profile_picture;
	            //$save_fb_page->page_access_token = $longLivedPageToken;
	            $save_fb_page->page_access_token = $longLivedPageToken;
	            $save_fb_page->status = 'Active';
	            $save_fb_page->save();
				//Session::forget('fb_user_id');
	            \Log::info('New page Inserted !');
	          }else{
	            $checkPage->fb_page_name = $page_name;
	            //$checkPage->fb_page_picture = $page_profile_picture;
	            $checkPage->page_access_token = $page_access_token;
	            $checkPage->save();
				//Session::forget('fb_user_id');
	            \Log::info('Update Existing page !');
	          }
		  }
		  return $fb_user_id;
  		} else {
			  return 'error';
  			 \Log::info('Error on access page information!');
  		}
    }

		public function getFormCountByPage(Request $request)
			{
			$user_id = Auth::user()->id;;
			if($user_id != '')
			{
			$returnArr = array();
			$pages = $request->input('pages');
			foreach($pages as $v)
			{
			  $returnArr[$v['id']]['image_path'] = $this->getPageProfilePicture($v['id'],$v['access_token']);

			  $url = "curl -G \
			-d 'access_token=".$v['access_token']."' \
			https://graph.facebook.com/".env("FB_APP_GRAPH_VERSION")."/".$v['id']."/leadgen_forms";
			  exec($url,$result);
			  $result =  implode('', $result);
			  $result = json_decode($result);
			  $returnArr[$v['id']]['form_count'] = COUNT($result->data);
			}
			return $returnArr;
			}
			else
			{
			return 0;
			}
		}

		public function getPageProfilePicture($pageId,$access_token)
		{
		  $fb = new Facebook([
		    'app_id'                => env("FB_APP_ID"),
		    'app_secret'            => env("FB_APP_SECRET"),
		    'default_graph_version' => env("FB_APP_GRAPH_VERSION")
		  ]);
		  $fb->setDefaultAccessToken($access_token);
		  $pictureResponse = $fb->sendRequest('GET', $pageId, ['fields' => 'picture'])
		  ->getDecodedBody();
		  return $pictureResponse['picture']['data']['url'];
		}

		public function generateAccessToken($userAccessToken){
			$fb = new Facebook([
			'app_id'                => env("FB_APP_ID"),
			'app_secret'            => env("FB_APP_SECRET"),
			'default_graph_version' => env("FB_APP_GRAPH_VERSION")
			]);
			$longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken($userAccessToken);

			print_r($longLivedToken);
			$fb->setDefaultAccessToken($longLivedToken);

			$arra=(array) $longLivedToken;
			$i=0;
			$keyaccess="";
			foreach ($arra as $key => $value) {
			if($i==0){
			$keyaccess=$value;
			}
			$i++;
			}
			return $keyaccess;
		}

		public function generatePageAccessToken($pageId, $page_access_token){
			$access_token = (string) Session::get('longLivedToken');
			$fb = new Facebook([
			'app_id'                => env("FB_APP_ID"),
			'app_secret'            => env("FB_APP_SECRET"),
			'default_graph_version' => env("FB_APP_GRAPH_VERSION")
			]);
			$fb->setDefaultAccessToken($access_token);
			$response = $fb->sendRequest('GET', $pageId, ['fields' => 'access_token'])
			->getDecodedBody();
			$foreverPageAccessToken = $response['access_token'];
			return $foreverPageAccessToken;

		}
}
