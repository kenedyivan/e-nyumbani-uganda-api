<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;

class RegisterController extends Controller
{
    function register(Request $request){

    	$resp = array();

    	$firstName = $request->input('first_name');
    	$lastName = $request->input('last_name');
    	$username = $request->input('username');
    	$email = $request->input('email');
    	$password = $request->input('password');
    	$loginType = 1;

    	$email_check = Agent::where('email',$email)->take(1)->get();

    	if($email_check->count() > 0){
				$resp['msg'] = 'Email taken';
				$resp['id'] = 0;
				$resp['error'] = 2;
				$resp['success'] = 0;
    	}else{

	    	$agent = new Agent();

	    	$agent->first_name = $firstName;
	    	$agent->last_name = $lastName;
	    	$agent->username = $username;
	    	$agent->email = $email;
	    	$agent->password = bcrypt($password);
	    	$agent->login_type = $loginType;
	    	$agent->profile_picture = "no_profile_picture";

	    	if($agent->save()){
					$resp['msg'] = 'Sign up successful';
					$resp['id'] = $agent->id;
					$resp['login_type'] = $agent->login_type;
					$resp['error'] = 0;
					$resp['success'] = 1;
	    	}else{
		    		$resp['msg'] = 'Sign up failed';
					$resp['id'] = 0;
					$resp['login_type'] = 0;
					$resp['error'] = 1; 
					$resp['success'] = 0;;
		    }
    	}

	    return json_encode($resp);
    }
}
