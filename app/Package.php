<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //package properties
    function p(){
        return $this->hasMany('App\Property','package_id', 'id');
    }


}
