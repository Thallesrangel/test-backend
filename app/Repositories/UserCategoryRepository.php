<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class UserCategoryRepository 
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAllUserCategory()
    {
        $this->model = $this->model->get();
        return $this->model;
    }
}
