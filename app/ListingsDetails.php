<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingsDetails extends Model
{
    //
    protected $table = 'property_details';

    public function orders(){
        return $this->hasMany('App\Listings','idProperty');
    }
}
