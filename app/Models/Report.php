<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
   public function user(){
 return $this->belongsTo(User::class);
}

public function facility(){
 return $this->belongsTo(Facility::class);
}

public function comments(){
 return $this->hasMany(Comment::class);
}
}
