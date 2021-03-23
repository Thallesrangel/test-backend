<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\User;

class UserCategory extends Model
{
    protected $table = "user_category";
    protected $primaryKey  = 'id_user_category';
    protected $fillable = [ 'id_user_category', 'category' ];
}
