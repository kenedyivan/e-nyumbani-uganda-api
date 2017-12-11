<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;

class AgentsController extends Controller
{
    function getAgents(){

    	$m_agents = array(); //All properties
        $a = array(); // A property

    	$agents = Agent::where('suspended',0)
    	->where('user_type',1)
    	->get();

    	foreach ($agents as $agent) {

    		$a['id'] = $agent->id;
            $a['first_name'] = $agent->first_name;
            $a['last_name'] = $agent->last_name;
            $a['company'] = $agent->company;
            $a['all'] = $agent->properties->count();
            $a['sale'] = $agent->properties->where('for_sale',1)->count();
            $a['rent'] = $agent->properties->where('for_rent',1)->count();
            $a['image'] = $agent->profile_picture;

            array_push($m_agents, $a);
    	}

    	return json_encode($m_agents);
    }
}
