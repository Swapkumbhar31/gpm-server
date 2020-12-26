<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    //
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function image()
    {
        return $this->hasOne('App\Media', 'filename', 'pic');
    }
    public function video()
    {
        return $this->hasOne('App\Media', 'filename', 'video_id');
    }
    public function module()
    {
        return $this->belongsTo('App\Module', 'module_id', 'id');
    }
    public function questionCount()
    {
        return $this->hasMany('App\Question', 'chapter_id', 'id');
    }
}
