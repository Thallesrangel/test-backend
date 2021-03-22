<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\model\UserCategory;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey  = 'id_user';
    protected $fillable = ['name', 'user_category_id', 'document', 'email', 'password'];

    public function category()
    {
        return $this->belongsTo(UserCategory::class, 'user_category_id', 'id_user_category');
    }
}
