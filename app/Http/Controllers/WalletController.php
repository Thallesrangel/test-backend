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

    public function update(Request $request, $id)
    {
        // Add transaction amount 
    }
}
