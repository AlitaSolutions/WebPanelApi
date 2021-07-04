<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Service extends Model
{
    protected $fillable = ['name','platform_id','is_enabled'];
    protected $hidden = ['created_at','updated_at','is_enabled','recommended','platform_id'];

    function platform(){
        return $this->belongsTo(Platform::class,'platform_id');
    }
    function servers(){
        return $this->hasMany(Server::class,'service_id');
    }
    function properties(){
        return $this->hasMany(Property::class,'service_id');
    }
    function getGroupsAttribute() {
        return DB::table('groups')
            ->join('service_groups','groups.id','=','service_groups.group_id')
            ->select('groups.*','service_groups.*')
            ->where('service_groups.service_id', '=', $this->id)->get();

    }
}
