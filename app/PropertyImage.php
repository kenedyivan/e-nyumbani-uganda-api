<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    protected $fillable = array('image');

    public function property(){
        return $this->belongsTo('App\Property');
    }
}
