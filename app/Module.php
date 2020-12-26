<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    public function chapters(){
        return $this->hasMany(Chapter::class);
    }
}
