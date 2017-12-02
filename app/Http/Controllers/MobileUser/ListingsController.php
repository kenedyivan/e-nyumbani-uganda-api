<?php

namespace App\Http\Controllers\MobileUser;

use App\Property;
use App\PropertyImage;
use App\PropertyCloudImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Cloudinary\Api;
use App\CloudinaryImage;

class ListingsController extends Controller
{
    function __construct(){
        parent::__construct();
    }

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
            $p['currency'] = strtolower($property->currency);
            //$p['image'] = $property->image;
            $p['image'] = cloudinary_url($property->image);

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
            $p['currency'] = strtolower($property->currency);
            //$p['image'] = $property->image;
            $p['image'] = cloudinary_url($property->image);

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
            $p['currency'] = strtolower($property->currency);
            //$p['image'] = $property->image;
            $p['image'] = cloudinary_url($property->image);

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
                $p['currency'] = strtolower($property->currency);
                //$p['image'] = $property->image;
                $p['image'] = cloudinary_url($property->image);

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
        $p['currency'] = strtolower($property->currency);


        

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

        //$p['main_image'] = $property->image;
        $p['main_image'] = cloudinary_url($property->image);

        $otherImages = $property->images;
        //array_unshift($otherImages, $property->image);

        //$p['other_images'] = $otherImages;

        $img_arr = array();

        $i = array();

        foreach ($otherImages as $img) {
        	//$i["image"] = $img->image;
            $i['image'] = cloudinary_url($img->image);
        	array_push($img_arr, $i);
        }

        $img_main = array();

        //$img_main["image"] = $property->image;
        $img_main['image'] = cloudinary_url($property->image);

        array_unshift($img_arr, $img_main);

        $p['other_images'] = $img_arr;

        

        return json_encode($p);


    }


    function uploadPhoto(){
        $resp = array();
        $cloudResult;

        if(isset($_POST["image"])) {
 
            $encoded_string = $_POST["encoded_string"];
            $image_name = $_POST["image"];
         
            $decoded_string = base64_decode($encoded_string);
         
            $path = public_path().'/uploads/'.$image_name;
         
            $file = fopen($path,'wb');
            $is_written = fwrite($file,$decoded_string);
            if($is_written){
                fclose($file);
                $cloudResult = $this->cloudinaryUploader($image_name);
            }else{
                $resp['msg'] = "failed";
                return json_encode($resp);
            }

            $secure_url = $cloudResult["secure_url"];
            $public_id = $cloudResult["public_id"];
            $format = $cloudResult["format"];
            $height = $cloudResult["height"];
            $width = $cloudResult["width"];
            $bytes = $cloudResult["bytes"];
            $session_time = $_POST["session_time"];
            $image = basename($secure_url);

            $CloudinaryImage = new CloudinaryImage();

            $CloudinaryImage->secure_url = $secure_url;
            $CloudinaryImage->public_id = $public_id;
            $CloudinaryImage->format = $format;
            $CloudinaryImage->height = $height;
            $CloudinaryImage->width = $width;
            $CloudinaryImage->bytes = $bytes;
            $CloudinaryImage->base_name = $image;
            $CloudinaryImage->session_time = $session_time;

            if ($CloudinaryImage->save()) {
                $resp['msg'] = "success";
                $resp['secure_url'] = $secure_url;
                $resp['image'] = $image;
                $resp['session_time'] = $session_time;
            } else {
                $resp['msg'] = "failed saving to database";
                return json_encode($resp);
            }

        }else{
            $resp['msg'] = "No image found";
        }

        
        return json_encode($resp);
    }

    function createProperty(Request $request){
        $resp = array();

        /*$resp['title'] = $request->input('title');
        $resp['description'] = $request->input('description');
        $resp['price'] = $request->input('price');
        $resp['type'] = $request->input('type');
        $resp['status'] = $request->input('status');
        $resp['currency'] = $request->input('currency');
        $resp['address'] = $request->input('address');
        $resp['district'] = $request->input('district');
        $resp['town'] = $request->input('town');
        $resp['region'] = $request->input('region');
        $resp['session_time'] = $request->input('session_time');*/

        $title = $request->input('title');
        $description = $request->input('description');
        $price = $request->input('price');
        $type = $request->input('type');
        $status = $request->input('status');
        $currency = $request->input('currency');
        $address = $request->input('address');
        $district = $request->input('district');
        $town = $request->input('town');
        $region = $request->input('region');
        $session_time = $request->input('session_time');

        $property = new Property();

        $property->title = $title;
        $property->description = $description;
        $property->agent_id = 11;
        $property->property_type_id = 1;
        $property->package_id = 1;
        $property->status = 0;
        $property->of_value = 0;

        if ($status == 'Sale') {
            $property->for_sale = 1;
        } else {
            $property->for_sale = 0;
        }

        if ($status == 'Rent') {
            $property->for_rent = 1;
        } else {
            $property->for_rent = 0;
        }


        $property->price = $price;
        $property->currency = strtolower($currency);

        $r_pid = substr(md5(rand()), 0, 9);
        $r_pid = 'prod' . $r_pid;
        $property->propertyID = $r_pid;

        $property->address = $address;
        $property->district = $district;
        $property->town = $town;
        $property->region = $region;
        $property->country = "uganda";

        $property->image = "no-image";

        if ($property->save()) {

            $cur_property = Property::find($property->id);

            //finds cloud images using session time
            $sessionTime = $session_time;
            $imgs = CloudinaryImage::where('session_time', $sessionTime)
                ->orderBy('created_at', 'asc')
                ->get();
            $f_img_id = null;

            foreach ($imgs as $img) {
                $imgName = $img->base_name;

                $propertyCloudImage = new PropertyCloudImage();

                $propertyCloudImage->feature_id = 1;
                $propertyCloudImage->secure_url = $img->secure_url;
                $propertyCloudImage->public_id = $img->public_id;
                $propertyCloudImage->format = $img->format;
                $propertyCloudImage->height = $img->height;
                $propertyCloudImage->width = $img->width;
                $propertyCloudImage->bytes = $img->bytes;
                $propertyCloudImage->base_name = $img->base_name;
                $propertyCloudImage->session_time = $img->session_time;

                $cur_property->cloudImages()->save($propertyCloudImage);

                //execute to set main property image
                if ($cur_property->image == 'no-image') {
                    $cur_property->image = $imgName;
                    $cur_property->save();
                } else {
                    $property_image = new PropertyImage(['image' => $imgName]);
                    $cur_property->images()->save($property_image);
                }

            }

            $resp["property"] = $cur_property->id;
            $resp['msg'] = "Process successful";
            $resp['status'] = 1;
            $resp['error'] = 0;

        }else{
            $resp["property"] = 0;
            $resp['msg'] = "Save property failed";
            $resp['status'] = 0;
            $resp['error'] = 1;
        }

        return json_encode($resp);
    }

    function cloudinaryUploader($image_name)
    {
        
        $resp = \Cloudinary\Uploader::upload(public_path() . "/uploads/".$image_name);
        return $resp;

    }

}
