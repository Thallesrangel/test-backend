<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transaction";
    protected $primaryKey  = 'id_transaction';
}
