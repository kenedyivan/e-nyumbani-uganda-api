<?php

namespace App\Traits;

trait GenerateImagePath
{
    function log($msg)
    {
        echo '<pre>';
        echo date('Y-m-d h:i:s') . ':' . '(' . __CLASS__ . ') ' . $msg . '<br/>';
        echo '</pre>';
    }

    function __construct()
    {
        $this->setCloudinaryAttributes();
    }


    function setCloudinaryAttributes()
    {

        \Cloudinary::config(array(
            "cloud_name" => "nyumbani",
            "api_key" => "797423219223595",
            "api_secret" => "yojtlscOSecOzF_t_UqfFxY0rPM"
        ));

    }

    function getPropertyImage($imageName)
    {

        return cloudinary_url($imageName, array(
            "transformation" => array(
                array("width" => 150, "height" => 150))));

    }

    function getPropertyDetailImage($imageName)
    {

        return cloudinary_url($imageName, array(
            "transformation" => array(
                array("width" => 364, "height" => 224))));

    }
}