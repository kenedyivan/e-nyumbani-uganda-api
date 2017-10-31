<?php

namespace App\Http\Controllers\MobileUser;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListingsController extends Controller
{
    function getAllListings(Request $request){

    	$m_properties = array(); //All properties
    	$p = array(); // A property

        $properties = Property::where('active', 1)->orderBy('id','DESC')->get();

        foreach ($properties as $property) {
        	
        	$p['id'] = $property->id;
        	$p['title'] = $property->title;
        	$p['address'] = $property->address;
        	$p['agent'] = $property->agent->username;
        	$p['price'] = $property->price;
        	$p['image'] = $property->image;

        	array_push($m_properties, $p);
        }
        
        return json_encode($m_properties);
    }

    function getAllForRent(Request $request){
    	$m_properties_for_rent = array();
    	$p = array();

		$properties = $properties = Property::where('for_rent',1)->where('active', 1)->orderBy('id','DESC')->get();

        foreach ($properties as $property) {
        	
        	$p['id'] = $property->id;
        	$p['title'] = $property->title;
        	$p['address'] = $property->address;
        	$p['agent'] = $property->agent->username;
        	$p['price'] = $property->price;
        	$p['image'] = $property->image;

        	array_push($m_properties_for_rent, $p);
        }
        
        return json_encode($m_properties_for_rent);

    }

    function getAllForSale(Request $request){
    	$m_properties_for_sale = array();
    	$p = array();
    	
		$properties = $properties = Property::where('for_sale',1)->where('active', 1)->orderBy('id','DESC')->get();

        foreach ($properties as $property) {
        	
        	$p['id'] = $property->id;
        	$p['title'] = $property->title;
        	$p['address'] = $property->address;
        	$p['agent'] = $property->agent->username;
        	$p['price'] = $property->price;
        	$p['image'] = $property->image;

        	array_push($m_properties_for_sale, $p);
        }
        
        return json_encode($m_properties_for_sale);

    }
}
