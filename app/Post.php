<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    public function comments(){
      return $this->hasMany('App\Comment');
    }
    /**
     * Get the properties for the agent.
     */
    public function author()
    {
        return $this->belongsTo('App\Agent','admin_id');
    }
}
