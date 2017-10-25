<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  /**
   * Get the properties for the agent.
   */
  public function post()
  {
      return $this->belongsTo('App\Post');
  }

  // user who commented
	public function author()
	{
		return $this->belongsTo('App\Agent','user_id');
	}
	
	
}
