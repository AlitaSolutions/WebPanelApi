<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $hidden = ['updated_at','created_at'];
    protected $fillable= ['name'];
    function tags(){
        return $this->hasMany(ServerTag::class,'tag_id');
    }
}
