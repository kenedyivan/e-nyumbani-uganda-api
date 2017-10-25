<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Listings extends Model
{
    //
    protected $table = 'property';

    public function product(){
        return $this->belongsTo('App\ListingsDetails');
    }
}
