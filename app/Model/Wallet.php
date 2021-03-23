<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = "wallets";
    protected $primaryKey  = 'id_wallet';
    protected $fillable = [ 'id_wallet', 'id_user', 'amount' ];
}
