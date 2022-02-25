<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = ['name'];
    protected $hidden = ['created_at','updated_at'];
    function services(){
        return $this->hasMany(Service::class ,'platform_id');
    }
}
