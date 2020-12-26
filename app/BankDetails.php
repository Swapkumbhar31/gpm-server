<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    //
    public function user(){
        return $this->belongsTo('User');
   }
}
