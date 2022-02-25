<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = ['service_id','name','type'] ;
    protected $hidden = ['created_at','updated_at'];

    function service(){
        return $this->belongsTo(Service::class,'service_id');
    }
}
