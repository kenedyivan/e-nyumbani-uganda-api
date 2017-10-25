<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $table = 'reviews';

    public function property()
      {
          return $this->belongsTo('App\Property','property_id');
      }

    // user who favourited
    public function agent()
    {
      return $this->belongsTo('App\Agent','user_id');
    }
}
