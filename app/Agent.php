<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
  use Notifiable;

  protected $guard = 'agent';

  /**
   * Get the properties for the agent.
   */
  public function properties()
  {
      return $this->hasMany('App\Property');
  }

  public function comments(){
    return $this->hasMany('App\Comment','user_id');
  }

  public function agent(){
    return $this->hasMany('App\Review','user_id');
  }

  public function agent_favorites(){
      return $this->belongsToMany('App\Property','agent_favorites');
  }
}
