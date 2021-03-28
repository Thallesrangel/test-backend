<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('apiJwt')->except(['store']);
        $this->user = $user;
    }

    /**
     * @OA\Get(
     *     tags={"user"},
     *     path="/api/user",
     *     security={{"bearer_token":{}}},
     *     description="Retorna todos os usuários",
     *     @OA\Response(
     *         response=200,
     *         description="Usuários retornados com sucesso.",
     *     )
     * ),
    */

    public function index()
    {
        $userRepository = new UserRepository($this->user);
        return UserResource::collection($userRepository->selectAllUser());
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
        $request->validated();
        
        $user = $this->user;
        $user->name = $request->name;
        $user->id_user_category = $request->id_user_category;
        $user->document = $request->document;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $lastInsertIdUser = $user->id_user;

        $wallet = new Wallet();
        $wallet->id_user = $lastInsertIdUser;
        $wallet->amount = 0;
        $wallet->save();
        
        return response()->json(['success' => 'registered'], 201);
    }

    /**
     * @OA\Get(
     *     tags={"user"},
     *     path="/api/user/{user}",
     *     security={{"bearer_token":{}}},
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

        if ( empty($user) ) {
            throw ValidationException::withMessages(['user_not_exists' => 'usuário não existe.']);
        }

        if ( $user->id_user != Auth::user()->id_user ) {
            throw ValidationException::withMessages(['user_not_found' => 'Acesso ao usuário negado!']);
        }

        if ( !$user ) {
            throw ValidationException::withMessages(['not_found' => 'Usuário informado não encontrado.']);
        }
        
        return new UserResource($user);
    }

    public function update(UserRequest $request, $idUser)
    {

        $data = $this->user->find($idUser);

        if ( empty($data) ) {
            throw ValidationException::withMessages(['user_not_exists' => 'usuário não existe.']);
        }

        if ( $data->id_user != Auth::user()->id_user ) {
            throw ValidationException::withMessages(['user_acess_forbidden' => 'Acesso ao usuário negado.']);
        }

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

        if ( $data->id_user != Auth::user()->id_user ) {
            throw ValidationException::withMessages(['user_acess_forbidden' => 'Acesso ao usuário negado!']);
        }

        $data->flag_excluido = 1;
        
        if ( !$delete = $data->save() ) {
            return response()->json(['error' => 'usuário não deletado', 500]);
        }

        return response()->json(['response' => $delete], 200);
    }
}
