<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCategoryResource;
use App\Model\UserCategory;
use App\Repositories\UserCategoryRepository;

class UserCategoryController extends Controller
{
    protected $userCategory;

    public function __construct(UserCategory $userCategory)
    {
        $this->userCategory = $userCategory;
    }

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
        $userCategoryRepository = new UserCategoryRepository($this->userCategory);
        return UserCategoryResource::collection($userCategoryRepository->selectAllUserCategory());
    }
}
