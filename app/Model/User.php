<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\model\UserCategory;
use App\model\Wallet;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey  = 'id_user';
    protected $fillable = [ 'name', 'id_user_category', 'document', 'email', 'password' ];
    protected $hidden = [ 'password' ];

    public function category()
    {
        return $this->belongsTo(UserCategory::class, 'id_user_category');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'id_user');
    }
}
