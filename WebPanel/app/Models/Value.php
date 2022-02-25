<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = ['server_id','property_id','value'];
    protected $hidden = ['created_at','updated_at'];
    /**
     * @var mixed
     */

    function server(){
        return $this->belongsTo(Server::class,'server_id');
    }
    function property(){
        return $this->belongsTo(Property::class,'property_id');
    }
}
