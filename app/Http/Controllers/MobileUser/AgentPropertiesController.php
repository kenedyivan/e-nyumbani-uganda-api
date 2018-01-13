<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;
use App\Property;
use App\Traits\GenerateImagePath;

class AgentPropertiesController extends Controller
{
    use GenerateImagePath;

    function myProperties(Request $request)
    {
        $a_properties = array();
        $p = array();

        $id = $request->input('id');
        $agent = Agent::find($id);

        $agentProperties = $agent->properties()->orderBy('id', 'DESC')->get();

        foreach ($agentProperties as $property) {

            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['price'] = $property->price;
            $p['currency'] = strtolower($property->currency);
            $p['image'] = $this->getPropertyImage($property->image);

            array_push($a_properties, $p);

        }

        //return json_encode($a_properties);
        return $a_properties;
    }

    function agentProperties(Request $request)
    {

        $a_properties = array();
        $p = array();

        $id = $request->input('id');
        $agent = Agent::find($id);

        $agentProperties = $agent->properties()->orderBy('id', 'DESC')->get();

        foreach ($agentProperties as $property) {

            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['price'] = $property->price;
            $status = "";
            if ($property->for_sale == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            $p['status'] = $status;
            $p['currency'] = strtolower($property->currency);
            $p['image'] = $this->getPropertyImage($property->image);

            array_push($a_properties, $p);

        }

        //return json_encode($a_properties);
        return $a_properties;
    }

    function myProperty(Request $request)
    {

        $id = $request->input('id');

        $property = Property::findOrFail($id);

        $status = null;

        if ($property->for_sale = 1) {
            $status = "sale";
        }

        if ($property->for_rent = 1) {
            $status = "rent";
        }


        $p = array();

        $p['id'] = $property->id;
        $p['title'] = $property->title;
        $p['description'] = $property->description;
        $p['rating'] = $property->rating;
        $p['no_reviews'] = $property->reviews->count();
        $p['address'] = $property->address;
        $p['type'] = $property->type->name;
        $p['status'] = $status;
        $p['agent_id'] = $property->agent->id;
        $p['agent'] = $property->agent->username;
        $p['price'] = $property->price;
        $p['currency'] = strtolower($property->currency);
        $p['created_at'] = $property->created_at->format('M d, Y \a\t h:i a');

        //Sets favorite property flag

        //end of clean ou the reviews

        $p['main_image'] = $this->getPropertyImage($property->image);

        $otherImages = $property->images;

        //$p['other_images'] = $otherImages;

        $img_arr = array();

        $i = array();

        foreach ($otherImages as $img) {
            $i["image"] = $this->getPropertyImage($img->image);
            array_push($img_arr, $i);
        }

        $img_main = array();

        //$img_main["image"] = $property->image;
        $img_main['image'] = $this->getPropertyImage($property->image);

        array_unshift($img_arr, $img_main);

        $p['other_images'] = $img_arr;

        //return json_encode($p);
        return $p;
    }

}
