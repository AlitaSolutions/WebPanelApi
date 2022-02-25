<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value','platform_id'];
    protected $hidden = ['created_at','updated_at'];

    function platform(){
        return $this->belongsTo(Platform::class,'platform_id');
    }
}
