<?php

namespace App\Http\Controllers\MobileUser;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListingsController extends Controller
{
    function getAllListings(Request $request){
        $properties = Property::where('active', 1)->orderBy('id','DESC')->get();
        return json_encode($properties);
    }
}
