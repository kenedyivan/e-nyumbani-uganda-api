<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialProviders extends Model
{
  protected $fillable = ['provider_id','provider'];
    //
    function user()
    {
      return $this->belongsTo(User::class);
    }
}
