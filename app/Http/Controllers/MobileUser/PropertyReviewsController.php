<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Review;
use App\Property;
use App\Agent;

class PropertyReviewsController extends Controller
{
    function reviewProperty(Request $request){
        $resp = array();

        $propertyId = $request->input('property_id');
        $agentId = $request->input('agent_id');
        $rating = $request->input('rating');
        $review = $request->input('review');

        $reviewObj = new Review();

        $reviewObj->property_id  = $propertyId;
        $reviewObj->rating  = $rating;
        $reviewObj->review  = $review;
        $reviewObj->user_id  = $agentId;

        $property_rating = Property::find($propertyId);

        if($reviewObj->save()){

            $rate = Review::where('property_id',$propertyId)->get();
            $i = 0;
            $j = 0;
            $r = sizeof($rate);
            foreach ($rate as $rates){
                while ($i<$r){
                    $j=$j+$rate[$i]['rating']; $i++;
                }
            }
            if($j>0){
                $av = round($j/$r);
                $property_rating->rating = round($j/$r);
            }else{
                $property_rating->rating = 0;
            }

            //return $av;
            if($property_rating->save()){

                $resp['message']  = "rating successful";
                $resp['success']  = 1;
                $resp['error']  = 0;
                $resp['new_rating'] = $property_rating->rating;
                $resp['number_of_reviews'] = $property_rating->reviews->count();

                $rating_arr = array();
                $rating_arr["id"] = $reviewObj->id;
                $rating_arr["username"] = $reviewObj->agent->username;
                $rating_arr["rating"] = $reviewObj->rating;
                $rating_arr["review"] = $reviewObj->review;
                $rating_arr["profile_picture"] = $reviewObj->agent->profile_picture;
                $rating_arr["created_at"] = $reviewObj->created_at->format('M d, Y');

                $resp["rate"] = $rating_arr;

            }else{
                $resp['message']  = "failed rating property";
                $resp['success']  = 0;
                $resp['error']  = 1;
            }

        }else{
            $resp['message']  = "Error rating property";
            $resp['success']  = 0;
            $resp['error']  = 2;
        }

        return $resp;
    }
}
