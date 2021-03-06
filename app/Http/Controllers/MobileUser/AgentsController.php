<?php

namespace App\Http\Controllers\MobileUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agent;
use App\Traits\GenerateImagePath;

class AgentsController extends Controller
{
    use GenerateImagePath;

    function getAgents()
    {

        $m_agents = array(); //All properties
        $a = array(); // A property

        $agents = Agent::where('suspended', 0)
            ->where('user_type', 1)
            ->get();

        foreach ($agents as $agent) {

            $a['id'] = $agent->id;
            $a['first_name'] = $agent->first_name;
            $a['last_name'] = $agent->last_name;
            $a['company'] = $agent->company;
            $a['all'] = $agent->properties->count();
            $a['sale'] = $agent->properties->where('for_sale', 1)->count();
            $a['rent'] = $agent->properties->where('for_rent', 1)->count();
            $a['image'] = $agent->profile_picture;

            array_push($m_agents, $a);
        }

        //return json_encode($m_agents);
        return $m_agents;
    }

    function showAgent(Request $request)
    {
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
            $r['image'] = $this->getPropertyImage($rProperty->image);

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

        $a['agent_properties'] = $rProperties_arr;

        return $a;
    }

    function accountDetails(Request $request)
    {
        $resp = array();

        $agentId = $request->input('id');

        $agent = Agent::find($agentId);

        if ($agent) {
            $resp['first_name'] = $agent->first_name;
            $resp['last_name'] = $agent->last_name;
            $resp['username'] = $agent->username;
            if ($agent->user_type == 1) {
                $resp['user_type'] = "agent";
            } else {
                $resp['user_type'] = "user";
            }
            $resp['office_phone'] = $agent->office_phone;
            $resp['mobile_phone'] = $agent->mobile_phone;
            $resp['email'] = $agent->email;
            $resp['company'] = $agent->company;
            $resp['position'] = $agent->position;
            $resp['image'] = $agent->profile_picture;

            $resp['msg'] = 'Retried user account';
            $resp['error'] = 0;
            $resp['success'] = 1;
        } else {
            $resp['msg'] = 'Failed retrieving user account';
            $resp['id'] = 0;
            $resp['error'] = 1;
            $resp['success'] = 0;
        }

        return $resp;
    }

    function updateProfile(Request $request)
    {
        $resp = array();

        $agentId = $request->input('id');
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $userName = $request->input('username');
        $userType = $request->input('user_type');

        $agent = Agent::find($agentId);

        $agent->first_name = $firstName;
        $agent->last_name = $lastName;
        $agent->username = $userName;
        $agent->user_type = $userType;

        if ($agent->save()) {

            $resp['msg'] = 'Update successful';
            $resp['id'] = $agent->id;
            $resp['first_name'] = $agent->first_name;
            $resp['last_name'] = $agent->last_name;
            $resp['username'] = $agent->username;
            if ($agent->user_type == 1) {
                $resp['user_type'] = "agent";
            } else {
                $resp['user_type'] = "user";
            }

            $resp['error'] = 0;
            $resp['success'] = 1;

        } else {
            $resp['msg'] = 'Update failed';
            $resp['id'] = 0;
            $resp['error'] = 1;
            $resp['success'] = 0;

        }

        return json_encode($resp);

    }

    function updateContact(Request $request)
    {
        $resp = array();

        $agentId = $request->input('id');
        $officePhone = $request->input('office_phone');
        $mobilePhone = $request->input('mobile_phone');
        $email = $request->input('email');

        $agent = Agent::find($agentId);

        $agent->office_phone = $officePhone;
        $agent->mobile_phone = $mobilePhone;
        $agent->email = $email;

        if ($agent->save()) {

            $resp['msg'] = 'Update successful';
            $resp['id'] = $agent->id;
            $resp['office_phone'] = $agent->office_phone;
            $resp['mobile_phone'] = $agent->mobile_phone;
            $resp['email'] = $agent->email;

            $resp['error'] = 0;
            $resp['success'] = 1;

        } else {
            $resp['msg'] = 'Update failed';
            $resp['id'] = 0;
            $resp['error'] = 1;
            $resp['success'] = 0;

        }

        return json_encode($resp);

    }

    function updateCompany(Request $request)
    {
        $resp = array();

        $agentId = $request->input('id');
        $company = $request->input('company');
        $position = $request->input('position');

        $agent = Agent::find($agentId);

        $agent->company = $company;
        $agent->position = $position;

        if ($agent->save()) {

            $resp['msg'] = 'Update successful';
            $resp['id'] = $agent->id;
            $resp['company'] = $agent->company;
            $resp['position'] = $agent->position;

            $resp['error'] = 0;
            $resp['success'] = 1;

        } else {
            $resp['msg'] = 'Update failed';
            $resp['id'] = 0;
            $resp['error'] = 1;
            $resp['success'] = 0;

        }

        return json_encode($resp);

    }

    function uploadProfilePicture()
    {
        $resp = array();

        if (isset($_POST["image"])) {

            $encoded_string = $_POST["encoded_string"];
            $image_name = $_POST["image"];
            $id = $_POST["id"];

            $agent = Agent::find($id);

            $decoded_string = base64_decode($encoded_string);

            $path = public_path() . '/images/agents/profile_330x330/' . $image_name;

            $file = fopen($path, 'wb');
            $is_written = fwrite($file, $decoded_string);
            if ($is_written) {
                fclose($file);
                $agent->profile_picture = $image_name;
                $agent->picture_kind = 0;

                if($agent->save()){
                    $resp['msg'] = "Image upload successful";
                }else{
                    $resp['msg'] = "failed saving to database";
                    return json_encode($resp);
                }

            } else {
                $resp['msg'] = "failed";
                return json_encode($resp);
            }

        } else {
            $resp['msg'] = "No image found";
        }


        return json_encode($resp);
    }

}
