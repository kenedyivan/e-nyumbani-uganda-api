<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsLetterSubscriber extends Model
{
	
  public function newsLetters(){
    return $this->belongsToMany('App\NewsLetter','news_letter_logs');
  }
}
