<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCategoryResource;
use App\Model\UserCategory;

class UserCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"user category"},
     *     path="/api/user_category",
     *     description="Retorna todas as categorias de usuário",
     *     @OA\Response(
     *         response=200,
     *         description="Transações retornados com sucesso.",
     *     ),
     * ),
    */
    public function index()
    {
        $userCategory = UserCategory::get();
        return UserCategoryResource::collection($userCategory);
    }
}
