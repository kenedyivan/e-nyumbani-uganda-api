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

    function showAgent(Request $request){
        $agentId = $request->input('agent_id');

        $agent = Agent::find($agentId);

        $a = array();

        $a["profile_picture"] = $agent->profile_picture;
        $a["first_name"] = $agent->first_name;
        $a["last_name"] = $agent->last_name;
        $a["company"] = $agent->company;
        $a["office_phone"] = $agent->office_phone;
        $a["mobile_phone"] = $agent->mobile_phone;
        $a["email"] = $agent->email;

        $agentProperties = $agent->properties()->take(6)->get();


        $rProperties_arr = array();
        $r = array();
        foreach ($agentProperties as $rProperty) {
            $r['id'] = $rProperty->id;
            $r['title'] = $rProperty->title;
            $r['image'] = $rProperty->image;

            //Sets status sale or rent
            if($rProperty->for_sale == 1){
                $status = "for sale";
            }else{
                $status = "for rent";
            }

            $r['status'] = $status;
            $r['rating'] = $rProperty->rating;

            array_push($rProperties_arr, $r);
        }

        $a['agent_properties'] = $rProperties_arr;

        return $a;
    }
}
