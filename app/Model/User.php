<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\model\UserCategory;
use App\model\Wallet;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
