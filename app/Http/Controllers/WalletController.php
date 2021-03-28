<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Wallet;
use App\Http\Resources\WalletResource;

class WalletController extends Controller
{
    protected $user;
    protected $wallet;

    public function __construct()
    {
        $this->user = new User();
        $this->wallet = new Wallet();
    }

    /**
     * @OA\Get(
     *     tags={"wallet"},
     *     path="/api/wallet/{user}",
     *     security={{"bearer_token":{}}},
     *     description="Retorna a carteira do usuário",
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
     *         description="Carteira do usuário informada, retorna informações",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Usuário informado não encontrado."
     *     )
     * )
    */

    public function show($idUser)
    {
        $user = $this->user->find($idUser);
        
        if ( !$user ) {
            throw ValidationException::withMessages(['error' => 'Usuário informado não encontrado.']);
        }

        $wallet = $this->wallet->where('id_user', '=', $user->id_user)->first();

        if ( !$wallet ) {
            throw ValidationException::withMessages(['error' => 'Carteira do usuário informado não encontrada.']);
        }
        
        return new WalletResource($wallet);
    }

}
