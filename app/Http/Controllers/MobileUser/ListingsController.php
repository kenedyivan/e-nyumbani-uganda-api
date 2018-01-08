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
use App\Agent;
use App\PropertyType;

class ListingsController extends Controller
{
    function __construct()
    {
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
            $p['agent_id'] = $property->agent->id;
            $p['agent'] = $property->agent->username;
            $p['price'] = $property->price;
            $p['currency'] = strtolower($property->currency);
            //$p['image'] = $property->image;
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

        foreach ($properties as $property) { ///todo : add agent id as part of this payload

            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['agent_id'] = $property->agent->id;
            $p['agent'] = $property->agent->username;
            $p['price'] = $property->price;
            $p['currency'] = strtolower($property->currency);
            //$p['image'] = $property->image;
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

        foreach ($properties as $property) { ///todo : add agent id as part of this payload

            $p['id'] = $property->id;
            $p['title'] = $property->title;
            $p['rating'] = $property->rating;
            $p['address'] = $property->address;
            $p['agent_id'] = $property->agent->id;
            $p['agent'] = $property->agent->username;
            $p['price'] = $property->price;
            $p['currency'] = strtolower($property->currency);
            //$p['image'] = $property->image;
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

            foreach ($results as $property) { ///todo : add agent id as part of this payload

                $p['id'] = $property->id;
                $p['title'] = $property->title;
                $p['rating'] = $property->rating;
                $p['address'] = $property->address;
                $p['agent_id'] = $property->agent->id;
                $p['agent'] = $property->agent->username;
                $p['price'] = $property->price;
                $p['currency'] = strtolower($property->currency);
                //$p['image'] = $property->image;
                $p['image'] = $property->image;

                array_push($search_properties, $p);
            }
            return json_encode($search_properties);
        } else {
            //Search query is empty
        }
    }

    public function showProperty(Request $request)
    {
        $id = $request->input('property_id');
        $agent_id = $request->input('agent_id');

        $property = Property::find($id);

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
        $favorite = Agent::find($agent_id)->agent_favorites()
            ->where('property_id', $property->id)
            ->get();

        if ($favorite->count() > 0) {
            $fav_flag = 1;
        } else {
            $fav_flag = 0;
        }

        $p['favorite'] = $fav_flag;

        $pAgent = array();

        $pAgent["first_name"] = $property->agent->first_name;
        $pAgent["last_name"] = $property->agent->last_name;
        $pAgent["company"] = $property->agent->company;

        $p['agent'] = $pAgent;

        //clean out the reviews
        $r_arr = array();
        $reviews = $property->reviews()->orderBy('id', 'DESC')->take(3)->get();

        $rrr = array();

        foreach ($reviews as $review) {
            $r_arr['id'] = $review->id;
            $r_arr['rating'] = $review->rating;
            $r_arr['review'] = $review->review;
            $r_arr['created_at'] = $review->created_at->format('M d, Y');
            $r_arr['username'] = $review->agent->username;
            $r_arr['profile_picture'] = $review->agent->profile_picture;

            array_push($rrr, $r_arr);
        }

        $p['reviews'] = $rrr;

        //end of clean ou the reviews

        //$p['main_image'] = $property->image;
        $p['main_image'] = $property->image;

        $otherImages = $property->images;
        //array_unshift($otherImages, $property->image);

        //$p['other_images'] = $otherImages;

        $img_arr = array();

        $i = array();

        foreach ($otherImages as $img) {
            $i["image"] = $img->image;
            //$i['image'] = cloudinary_url($img->image);
            array_push($img_arr, $i);
        }

        $img_main = array();

        //$img_main["image"] = $property->image;
        $img_main['image'] = $property->image;

        array_unshift($img_arr, $img_main);

        $p['other_images'] = $img_arr;

        //Retreives related properties

        $propertyType = $property->type->id;

        $relatedProperties = Property::where('property_type_id', $propertyType)
            ->where('id', '!=', $property->id)
            ->get();

        $rProperties_arr = array();
        $r = array();
        foreach ($relatedProperties as $rProperty) {
            $r['id'] = $rProperty->id;
            $r['title'] = $rProperty->title;
            $r['image'] = $rProperty->image;

            //Sets status sale or rent
            if ($rProperty->for_sale == 1) {
                $status = "for sale";
            } else {
                $status = "for rent";
            }

            $r['status'] = $status;
            $r['rating'] = $rProperty->rating;

            array_push($rProperties_arr, $r);
        }

        $p['related_properties'] = $rProperties_arr;

        //return json_encode($p);
        return $p;


    }


    function uploadPhoto()
    {
        $resp = array();
        $cloudResult;

        if (isset($_POST["image"])) {

            $encoded_string = $_POST["encoded_string"];
            $image_name = $_POST["image"];

            $decoded_string = base64_decode($encoded_string);

            $path = public_path() . '/uploads/' . $image_name;

            $file = fopen($path, 'wb');
            $is_written = fwrite($file, $decoded_string);
            if ($is_written) {
                fclose($file);
                $cloudResult = $this->cloudinaryUploader($image_name);
            } else {
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

        } else {
            $resp['msg'] = "No image found";
        }


        return json_encode($resp);
    }


    function createProperty(Request $request)
    {
        $resp = array();

        $title = $request->input('title');
        $agentId = $request->input('agent_id');
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
        $property->agent_id = $agentId;
        $property->property_type_id = $type;
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

        } else {
            $resp["property"] = 0;
            $resp['msg'] = "Save property failed";
            $resp['status'] = 0;
            $resp['error'] = 1;
        }

        return json_encode($resp);
    }

    function cloudinaryUploader($image_name)
    {

        $resp = \Cloudinary\Uploader::upload(public_path() . "/uploads/" . $image_name);
        return $resp;

    }

    function editProperty(Request $request)
    {
        $id = $request->input('id');
        $property = Property::find($id);
        $prop = array();

        $prop['title'] = $property->title;
        $prop['description'] = $property->description;
        $prop['price'] = $property->price;
        if ($property->for_sale == 1) {
            $status = 1;
        } else {
            $status = 0;
        }
        $prop['status'] = $status;
        $prop['currency'] = $property->currency;
        $prop['address'] = $property->address;
        $prop['district'] = $property->district;
        $prop['town'] = $property->town;
        $prop['region'] = $property->region;


        //builds types array
        $set_type_id = $property->type->id;
        $types = PropertyType::where('id', '<>', $set_type_id)->get();

        $types_arr = array();

        $t = array();

        foreach ($types as $type) {
            $t["id"] = $type->id;
            $t["name"] = $type->name;
            array_push($types_arr, $t);
        }

        $setType = PropertyType::find($set_type_id);

        $setType_obj = array();
        $setType_obj["id"] = $setType->id;
        $setType_obj["name"] = $setType->name;

        array_unshift($types_arr, $setType_obj);

        $prop["types"] = $types_arr;
        //Ends types array

        //builds status array

        //Ends status array

        //Images array
        $p['main_image'] = $property->image;

        $otherImages = $property->images;

        $img_arr = array();

        $i = array();

        foreach ($otherImages as $img) {
            $i["image"] = $img->image;
            //$i['image'] = cloudinary_url($img->image);
            array_push($img_arr, $i);
        }

        $img_main = array();

        //$img_main["image"] = $property->image;
        $img_main['image'] = $property->image;

        array_unshift($img_arr, $img_main);

        $prop['images'] = $img_arr;

        //Ends images array

        return $prop;


    }

    function save(Request $request)
    {

        $resp = array();

        $id = $request->input('id');
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

        $property = Property::find($id);

        $orig_property = $property;

        //Original data
        $o_title = $property->title;
        $o_description = $property->description;
        $o_type = $property->property_type_id;

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


        $o_price = $property->price;
        $o_currency = $property->currency;

        $o_address = $property->address;
        $o_district = $property->district;
        $o_town = $property->town;
        $o_region = $property->region;
        //End of original data

        $property->title = $title;
        $property->description = $description;
        $property->property_type_id = $type;
        $property->package_id = 1;
        $property->status = 0;
        $property->of_value = 0;

        if ($property->for_sale == 1) {
            $o_status = 'Sale';
        } else{
            $o_status = 'Rent';
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

        if ($property->save()) {
            $prop = array();

            if($property->title != $o_title)
                $prop['title'] = $o_title;

            if($property->description != $o_description)
                $prop['description'] = $property->description;

            if($property->property_type_id != $o_type)
                $prop['type'] = $property->property_type_id;


            if ($property->for_sale == 1) {
                $r_status = 'Sale';
            } else{
                $r_status = 'Rent';
            }

            if($r_status != $o_status)
                $prop['status'] = $r_status;


            if($property->price != $o_price)
                $prop['price'] = $property->price;

            if($property->currency != $o_currency)
                $prop['currency'] = $property->currency;


            if($property->address != $o_address)
                $prop['address'] = $property->address;

            if($property->district != $o_district)
                $prop['district'] = $property->district;

            if($property->town != $o_town)
                $prop['town'] = $property->town;

            if($property->region != $o_region)
                $prop['region'] = $property->region;


            $resp["property"] = $property->id;
            $resp['msg'] = "Process successful";
            $resp['prop'] = $prop;
            $resp['status'] = 1;
            $resp['error'] = 0;
        } else {
            $resp["property"] = 0;
            $resp['msg'] = "Save property failed";
            $resp['status'] = 0;
            $resp['error'] = 1;
        }

        return $resp;

    }

    function updatePhoto()
    {
        $resp = array();
        $cloudResult;

        if (isset($_POST["image"])) {

            $encoded_string = $_POST["encoded_string"];
            $image_name = $_POST["image"];
            $id = $_POST["id"];

            $current_image_name = $_POST["current_image_name"];
            if($current_image_name != "" || $current_image_name != null){

                $property_image = PropertyImage::where("image",$current_image_name);
                $property_image->forceDelete();
            }

            $decoded_string = base64_decode($encoded_string);

            $path = public_path() . '/uploads/' . $image_name;

            $file = fopen($path, 'wb');
            $is_written = fwrite($file, $decoded_string);
            if ($is_written) {
                fclose($file);
                $cloudResult = $this->cloudinaryUploader($image_name);
            } else {
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

                $cur_property = Property::find($id);

                //execute to set main property image
                if ($cur_property->image == 'no-image') {
                    $cur_property->image = $CloudinaryImage->base_name;
                    $cur_property->save();
                } else {
                    $property_image = new PropertyImage(['image' => $CloudinaryImage->base_name]);
                    $cur_property->images()->save($property_image);
                }
            } else {
                $resp['msg'] = "failed saving to database";
                return json_encode($resp);
            }

        } else {
            $resp['msg'] = "No image found";
        }


        return json_encode($resp);
    }

    function propertyTypes(){
        $resp = array();
        $types = PropertyType::all();

        $types_arr = array();

        $t = array();

        foreach ($types as $type) {
            $t["id"] = $type->id;
            $t["name"] = $type->name;
            array_push($types_arr, $t);
        }

        $resp["types"] = $types_arr;

        return $resp;
    }
}
