<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRepository 
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAllUser()
    {
        $this->model = $this->model->with('category')
                    ->where('id_user', '=', Auth::user()->id_user)
                    ->where('flag_excluido', '=', 0)
                    ->get();
                    
        return $this->model;
    }
}
