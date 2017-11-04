<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /**
     * Get the agent that owns the property.
     */
    public function agent()
    {
        return $this->belongsTo('App\Agent');
    }

    public function package()
    {
        return $this->belongsTo('App\Package');
    }

    public function type(){
        return $this->belongsTo('App\PropertyType','property_type_id');
    }

    public function images(){
        return $this->hasMany('App\PropertyImage');
    }

    public function favorites_to_agent(){
        return $this->belongsToMany('App\Agent','agent_favorites');
    }

    public function reviews(){
        return $this->hasMany('App\Review');
    }
}
