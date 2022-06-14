<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Validator;


class socialAuth extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/';
    protected $dbuser = '';
    protected $provider = '';


    public function facebook(Request $request){
        $client = new GuzzleHttp\Client();
        $social = App\Settings::getSocialKeys();
        $params = [
            'code' => $request->code,
            'client_id' => $social['facebook_client_id']->value,
            'redirect_uri' => $social['facebook_redirect_url']->value,
            'client_secret' => $social['facebook_client_secret']->value
        ];
        $accessTokenResponse = $client->request('GET', 'https://graph.facebook.com/v2.5/oauth/access_token', [
            'query' => $params
        ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);
        $fields = 'id,email,first_name,last_name,link,name';
        $profileResponse = $client->request('GET', 'https://graph.facebook.com/v2.5/me', [
            'query' => [
                'access_token' => $accessToken['access_token'],
                'fields' => $fields
            ]
        ]);
        $profile = json_decode($profileResponse->getBody(), true);
        if(!isset($profile['email'])){
            return json_encode(['state' => 'failed' ,'message' => getPhrase('invalid_social_login')]);
        }
        $email = $profile['email'];
        $user = App\User::where('email',$email)->first();
        if(!$user){
            return json_encode(['state' => 'failed' ,'message' => getPhrase('invalid_social_login')]);
        }
        $this->postLogin($user->id);
        $loggedUser = new App\UsersLogin();
        $loggedUser->users_id = $user->id;
        $loggedUser->user_stamp($request);
        $loggedUser->save();

        return json_encode(['state' => 'success' ,'message' => getPhrase('login_success')]);
    }

    public function google(Request $request){
        $client = new GuzzleHttp\Client();

        $social = App\Settings::getSocialKeys();

        $params = [
            'code' => $request->code,
            'client_id' => $social['google_client_id']->value,
            'client_secret' => $social['Google_client_secret']->value,
            'redirect_uri' => $social['google_redirect_url']->value,
            'grant_type' => 'authorization_code',
        ];
        $accessTokenResponse = $client->request('POST', 'https://accounts.google.com/o/oauth2/token', [
            'form_params' => $params
        ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);
        $profileResponse = $client->request('GET', 'https://www.googleapis.com/plus/v1/people/me/openIdConnect', [
            'headers' => array('Authorization' => 'Bearer ' . $accessToken['access_token'])
        ]);
        $profile = json_decode($profileResponse->getBody(), true);
        if(!isset($profile['email'])){
            return json_encode(['state' => 'failed' ,'message' => getPhrase('invalid_social_login')]);
        }
        $email = $profile['email'];
        $user = App\User::where('email',$email)->first();
        if(!$user){
            return json_encode(['state' => 'failed' ,'message' => getPhrase('invalid_social_login')]);
        }
        $this->postLogin($user->id);
        $loggedUser = new App\UsersLogin();
        $loggedUser->users_id = $user->id;
        $loggedUser->user_stamp($request);
        $loggedUser->save();

        return json_encode(['state' => 'success' ,'message' => getPhrase('login_success')]);
    }

    public function postLogin($sent)
    {
        $login_status = FALSE;
        if ($user=Auth::loginUsingId($sent)) {
            // return redirect(PREFIX);
            if ($user === true) {
                $login_status = true;
            }
            elseif ($user === 'detained')
            {
                return redirect()->back()
                    ->withErrors([
                        getPhrase('this_user_is_detained')
                    ]);
            }
        }

        elseif ($user=Auth::loginUsingId($sent)) {
            if ($user === true) {
                $login_status = true;
            }
            elseif ($user === 'detained')
            {
                return redirect()->back()
                    ->withErrors([
                        getPhrase('this_user_is_detained')
                    ]);
            }
        }


        /**
         * Check if the logged in user is parent or student
         * if parent check if admin enabled the parent module
         * if not enabled show the message to user and logout the user
         */

        if($login_status) {
            if(checkRole(getUserGrade(7)))  {
                if(!getSetting('parent', 'module')) {
                    return redirect(URL_PARENT_LOGOUT);
                }
            }
        }

        /**
         * The logged in user is student/admin/owner
         */
        if($login_status)
        {
            session()->put('is_student', '0');
            if(checkRole(getUserGrade(5)))
            {
                $user = App\User::where('id','=',$sent)->first();
                if($user)
                {
                    session()->put('is_student', '1');
                    session()->put('user_record', prepareStudentSessionRecord($user->slug));


                }
            }
            App\Language::resetLanguage();
            return redirect(PREFIX);
        }


    }

}


