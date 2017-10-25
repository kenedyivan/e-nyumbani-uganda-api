<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    //
    public function subscribers(){
      return $this->belongsToMany('App\NewsLetterSubscriber','news_letter_logs');
    }
}
