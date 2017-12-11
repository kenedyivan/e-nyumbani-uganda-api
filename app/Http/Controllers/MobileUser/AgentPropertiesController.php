<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;
use App\Property;

class AgentPropertiesController extends Controller
{
    function myProperties(Request $request){
    	$a_properties = array();
    	$p = array();

    	$id = $request->input('id');
    	$agent = Agent::find($id);

    	$agentProperties = $agent->properties()->orderBy('id', 'DESC')->get();

    	foreach($agentProperties as $property){
			
			$p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['price'] = $property->price;
            $p['currency'] = strtolower($property->currency);
            $p['image'] = cloudinary_url($property->image);

            array_push($a_properties, $p);

    	}

    	//return json_encode($a_properties);
    	return $a_properties;
    }

    function agentProperties(Request $request){

        $a_properties = array();
        $p = array();

        $id = $request->input('id');
        $agent = Agent::find($id);

        $agentProperties = $agent->properties()->orderBy('id', 'DESC')->get();

        foreach($agentProperties as $property){
            
            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['price'] = $property->price;
            $status = "";
            if($property->for_sale == 1){
                $status = 0;
            }else{
                $status = 1;
            }
            $p['status'] = $status;
            $p['currency'] = strtolower($property->currency);
            $p['image'] = cloudinary_url($property->image);

            array_push($a_properties, $p);

        }

        return json_encode($a_properties);
        //return $a_properties;
    }

}
