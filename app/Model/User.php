<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\model\UserCategory;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey  = 'id_user';
    protected $fillable = [ 'name', 'id_user_category', 'document', 'email', 'password' ];
    protected $hidden = [ 'password' ];

    public function category()
    {
        return $this->hasMany(UserCategory::class, 'id_user_category');
    }
}
