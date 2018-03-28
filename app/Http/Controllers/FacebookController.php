<?php

namespace App\Http\Controllers;
use App\User;
use Socialite;
use App\SocialLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FacebookController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        // $user->token;
        // $user->token;
        $data = [
            'name' => $user->getName().' '.$user->getNickname(),
            'email' => $user->getEmail(),
            'password' => 'Facebook',
        ];
        $my_user = User::where('email','=', $user->getEmail())->first();
        if($my_user === null) {
            Auth::login(User::firstOrCreate($data));
            $user_id=User::where('email','=',$user->getEmail())->first()->id;
            $account=new SocialLogin();
            $account->user_id=$user_id;
            $account->provider_user_id=$user->getId();
            $account->provider= 'Facebook';
            $account->save();
        } else {
            Auth::login($my_user);
        }

        //after login redirecting to home page
        return redirect('/dashboard');


    }
}
