<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;
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
				$resp['error'] = 0;
				$resp['success'] = 1;
    		
	    	}else{
	    		$resp['msg'] = 'Incorrect password';
				$resp['id'] = 0;
				$resp['error'] = 1; 
				$resp['success'] = 0;;
	    	}

    	}else{
    		
    		$resp['msg'] = 'Login failed';
    		$resp['id'] = 0;
    		$resp['error'] = 2;
    		$resp['success'] = 0;

    	}

    	

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
