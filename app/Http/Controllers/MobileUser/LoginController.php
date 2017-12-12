<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;
use App\SocialId;
use Hash;

class LoginController extends Controller
{
    function login(Request $request){

    	$resp = array();

    	$email = $request->input('email');
    	$password = $request->input('password');    	

    	$user = Agent::where('email',$email)->take(1)
    	->get();


    	if($user->count() > 0){

    		if (Hash::check($password, $user[0]->password)){
	    		$resp['msg'] = 'Login successful';
				$resp['id'] = $user[0]->id;
				$resp['login_type'] = $user[0]->login_type;
				$resp['error'] = 0;
				$resp['success'] = 1;
    		
	    	}else{
	    		$resp['msg'] = 'Incorrect password';
				$resp['id'] = 0;
				$resp['login_type'] = 0;
				$resp['error'] = 1; 
				$resp['success'] = 0;;
	    	}

    	}else{
    		
    		$resp['msg'] = 'Login failed';
    		$resp['id'] = 0;
    		$resp['login_type'] = 0;
    		$resp['error'] = 2;
    		$resp['success'] = 0;

    	}

    	

    	return json_encode($resp);
    }

    function socialLogin(Request $request){

    	$resp = array();



    	$firstName = $request->input('first_name');
    	$lastName = $request->input('last_name');
    	$userName = $request->input('username');    	
    	$socialId = $request->input('social_id');
    	$loginType = 2;

    	//check if social id already there

    	$s = SocialId::where('social_id',$socialId)->take(1)->get();

    	if($s->count() > 0){

    		$resp['msg'] = 'Login successful';
			$resp['id'] = $s[0]->agent->id;
			$resp['s_id'] = $s[0]->social_id;
			$resp['login_type'] = $s[0]->agent->login_type;
			$resp['error'] = 0;
			$resp['success'] = 1;
    	}else{
			$user = new Agent();

	    	$user->first_name = $firstName;
	    	$user->last_name = $lastName;
	    	$user->username = $userName;
	    	$user->logint_type = $loginType;
	    	$user->profile_picture = "no_profile_picture";
	    	$user->password = Hash::make($socialId.$userName);

	    	if($user->save()){
	    		$u = Agent::find($user->id);
	    		$social_id = new SocialId();
	    		$social_id->social_id = $socialId;

	    		if($u->socialId()->save($social_id)){
	    			$resp['msg'] = 'Login successful';
					$resp['id'] = $user->id;
					$resp['s_id'] = $social_id->social_id;
					$resp['login_type'] = $user->login_type;
					$resp['error'] = 0;
					$resp['success'] = 1;
	    		}else{
	    		
	    		$resp['msg'] = 'Login failed';
	    		$resp['id'] = 0;
	    		$resp['s_id'] = 0;
	    		$resp['login_type'] = 0;
	    		$resp['error'] = 1;
	    		$resp['success'] = 0;

	    	}

	    	}else{
	    		
	    		$resp['msg'] = 'Failed getting social details';
	    		$resp['id'] = 0;
	    		$resp['s_id'] = 0;
	    		$resp['login_type'] = 0;
	    		$resp['error'] = 2;
	    		$resp['success'] = 0;

	    	}

    	}

    	//end check    	

    	return json_encode($resp);
    }

    function userProfile(Request $request){
    	$resp = array();

    	$id = $request->input('id');

    	$user = Agent::where('id',$id)->take(1)->get();

    	if($user->count() > 0){
    		$u = $user[0];
    		$resp['msg'] = "User profile";
    		$resp['success'] = 1;
    		$resp['error'] = 0;
    		$resp['firstname'] = strtolower($u->first_name);
    		$resp['lastname'] = strtolower($u->last_name);
    		$resp['email'] = $u->email;
    		$resp['profile_picture'] = $u->profile_picture;
    	}else{
    		$res['msg'] = "No profile found";
    		$resp['success'] = 0;
    		$resp['error'] = 1;
    	}

    	return json_encode($resp);
    }
}
