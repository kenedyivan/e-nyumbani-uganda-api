<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct(){
    	$this->setCloudinaryAttributes();
    }

    function setCloudinaryAttributes()
    {
        \Cloudinary::config(array(
            "cloud_name" => "kenedy",
            "api_key" => "149743782894548",
            "api_secret" => "6wbU33ZFnwrKcKBIugILwshdPws"
        ));

    }
}
