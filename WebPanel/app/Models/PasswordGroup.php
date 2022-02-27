<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordGroup extends Model
{
    protected $fillable = [
        "name",
        "username",
        "password"
    ];

    function servers() {
        return $this->belongsToMany(Server::class,"server_passwords","pg_id","server_id");
    }
}
