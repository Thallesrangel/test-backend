<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Wallet;

use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @OA\Get(
     *     tags={"user"},
     *     path="/api/user",
     *     description="Retorna todos os usuários",
     *     @OA\Response(
     *         response=200,
     *         description="Usuários retornados com sucesso.",
     *     )
     * ),
    */

    public function index()
    {
        $data = $this->user::with('category')->where('flag_excluido', '=', 0)->get();
        return UserResource::collection($data);
    }

    /**
     * @OA\Post(
     *     tags={"user"},
     *     path="/api/user",
     *     description="Criando novo usuário",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome completo do usuário",
     *         required=true,
     *          @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id_user_category",
     *         in="query",
     *         description="Categorias ( ID: 1 - Comum ou 2 - Lojistas)",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="document",
     *         in="query",
     *         description="Documento",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Senha",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registrado com sucesso.",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados ausentes."
     *     )
     * )
    */

    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        
        if ( $validated ) {
            $user = $this->user::create($request->all());
            $lastInsertIdUser = $user->id_user;

            # creating wallet with id user
            $wallet = new Wallet();
            $wallet->id_user = $lastInsertIdUser;
            $wallet->amount = 0;
            $wallet->save();
        }

        return response()->json(['success' => 'registered'], 201);
    }

    /**
     * @OA\Get(
     *     tags={"user"},
     *     path="/api/user/{user}",
     *     description="Retorna o usuário com id especificado",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário retornado com sucesso.",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Usuário informado não encontrado.",
     *      )
     * )
    */

    public function show($idUser)
    {
        $user = $this->user::find($idUser);
        
        if ( !$user ) {
            throw ValidationException::withMessages(['not_found' => 'Usuário informado não encontrado.']);
        }
        
        return new UserResource($user);
    }

    public function update(UserRequest $request, $id)
    {

        $data = $this->user->findOrFail($id);
        $validated = $request->validated();

        if ($validated) {
            $data->update($request->all());
        }

        return new UserResource($data);
    }

    public function destroy($idUser)
    {
        if ( !$data = $this->user->find($idUser) ) {
            return response()->json(['error' => 'usuário informado não encontrado.'], 404);
        }

        $data->flag_excluido = 1;
        
        if ( !$delete = $data->save() ) {
            return response()->json(['error' => 'usuário não deletado', 500]);
        }

        return response()->json(['response' => $delete]);
    }
}
