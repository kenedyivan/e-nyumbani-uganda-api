<?php

namespace App\Http\Controllers\MobileUser;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ListingsController extends Controller
{
    function getAllListings(Request $request)
    {

        $m_properties = array(); //All properties
        $p = array(); // A property

        $properties = Property::where('active', 1)->orderBy('id', 'DESC')->get();

        foreach ($properties as $property) {

            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['agent'] = $property->agent->username;
            $p['price'] = $property->price;
            $p['image'] = $property->image;

            array_push($m_properties, $p);
        }

        return json_encode($m_properties);
    }

    function getAllForRent(Request $request)
    {
        $m_properties_for_rent = array();
        $p = array();

        $properties = $properties = Property::where('for_rent', 1)->where('active', 1)->orderBy('id', 'DESC')->get();

        foreach ($properties as $property) {

            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['agent'] = $property->agent->username;
            $p['price'] = $property->price;
            $p['image'] = $property->image;

            array_push($m_properties_for_rent, $p);
        }

        return json_encode($m_properties_for_rent);

    }

    function getAllForSale(Request $request)
    {
        $m_properties_for_sale = array();
        $p = array();

        $properties = $properties = Property::where('for_sale', 1)->where('active', 1)->orderBy('id', 'DESC')->get();

        foreach ($properties as $property) {

            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['agent'] = $property->agent->username;
            $p['price'] = $property->price;
            $p['image'] = $property->image;

            array_push($m_properties_for_sale, $p);
        }

        return json_encode($m_properties_for_sale);

    }

    public function search(Request $request)
    {
        $search_properties = array();
        $p = array();

        $searchQuery = $request->input('query');
        if ($searchQuery) {
            //$properties = DB::table('properties');
            $results = Property::where('title', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('description', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('address', 'LIKE', '%' . $searchQuery . '%')
                ->get();

            foreach ($results as $property) {

                $p['id'] = $property->id;
                $p['title'] = $property->title;
                $p['rating'] = $property->rating;
                $p['address'] = $property->address;
                $p['agent'] = $property->agent->username;
                $p['price'] = $property->price;
                $p['image'] = $property->image;

                array_push($search_properties, $p);
            }
            return json_encode($search_properties);
        }else{
            //Search query is empty
        }
    }

    public function showProperty(Request $request){
        $id = $request->input('id');

        $property = Property::find($id);

        $status = null;

        if($property->for_sale = 1){
            $status = "sale";
        }

        if($property->for_rent = 1){
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
        $p['agent'] = $property->agent->username;
        $p['price'] = $property->price;


        

        //clean out the reviews
        $r_arr = array();
        $reviews = $property->reviews;

        $rrr = array();

        foreach($reviews as $review){
            $r_arr['id'] = $review->id;
            $r_arr['rating'] = $review->rating;
            $r_arr['review'] = $review->review;
            $r_arr['created_at'] = $review->created_at;
            $r_arr['username'] = $review->agent->username;
            $r_arr['profile_picture'] = $review->agent->profile_picture;

            array_push($rrr, $r_arr);
        }

        $p['reviews'] = $rrr;

        //end of clean ou the reviews

        $p['main_image'] = $property->image;

        $otherImages = $property->images;
        //array_unshift($otherImages, $property->image);

        //$p['other_images'] = $otherImages;

        $img_arr = array();

        $i = array();

        foreach ($otherImages as $img) {
        	$i["image"] = $img->image;
        	array_push($img_arr, $i);
        }

        $img_main = array();

        $img_main["image"] = $property->image;

        array_unshift($img_arr, $img_main);

        $p['other_images'] = $img_arr;

        

        return json_encode($p);


    }


}
