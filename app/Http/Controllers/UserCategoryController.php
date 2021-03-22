<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCategoryResource;
use App\Model\UserCategory;

class UserCategoryController extends Controller
{
    public function index()
    {
        $userCategory = UserCategory::get();
        return UserCategoryResource::collection($userCategory);
    }
}
