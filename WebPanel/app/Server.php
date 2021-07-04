<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Server extends Model
{
    protected $fillable = ['service_id'];
    protected $hidden = ['created_at','updated_at'];

    function properties(){
        return $this->hasMany(Value::class,'server_id');
    }
    function service(){
        return $this->belongsTo(Service::class, 'service_id');
    }
    function tags(){
        return $this->hasMany(ServerTag::class,'server_id');
    }
    function getGroupsAttribute(){
        return DB::table('groups')
            ->join('server_groups', 'groups.id', '=', 'server_groups.group_id')
            ->select('groups.*', 'server_groups.*')
            ->where('server_groups.server_id', '=', $this->id)->get();
    }
}
