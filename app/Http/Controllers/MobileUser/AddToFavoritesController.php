<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;
use App\Property;

class AddToFavoritesController extends Controller
{
    function addToFavorites(Request $request){
        $resp = array();

        $agentId = $request->input('agent_id');
        $propertyId = $request->input('property_id');

        $favorite = Agent::find($agentId)->agent_favorites()
            ->where('property_id',$propertyId)
            ->get();

        if($favorite->count()>0){
            return $this->removeFavorite($propertyId,$agentId);
        }else{
            $agent = Agent::find($agentId);

            $agent->agent_favorites()->attach($propertyId);

            $error = 0;
            $status = 1;
            $message = "Added to favorites";

        }

        $res['error'] = $error;
        $res['status'] = $status;
        $res['message'] = $message;

        return $res;

    }

    function removeFavorite($p_id,$a_id){
        $res = array();

        $property_id = $p_id;
        $agent_id = $a_id;

        $agent = Agent::find($agent_id);

        $agent->agent_favorites()->detach($property_id);
        $error = 0;
        $status = 2;
        $message = "Removed from favorites";


        $res['error'] = $error;
        $res['status'] = $status;
        $res['message'] = $message;

        return $res;
    }

    function getAgentFavourites(Request $request){
        $agentId = $request->input('agent_id');

        $favorites = Agent::find($agentId)->agent_favorites();

    }
}
