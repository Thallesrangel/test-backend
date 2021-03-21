<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey  = 'id_user';
    protected $fillable = ['name','document','email','password'];
}
