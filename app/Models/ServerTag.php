<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerTag extends Model
{
    protected $hidden = ['updated_at','created_at'];
    function tag(){
        return $this->belongsTo(Tag::class,'tag_id');
    }
    function server(){
        return $this->belongsTo(Server::class,'server_id');
    }
}
