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

    public function index()
    {
        $data = $this->user::with('category')->get();
        return UserResource::collection($data);
    }

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
         if( !$data = $this->user->find($idUser)) {
            return response()->json(['error' => 'usuário informado não encontrado.'], 404);
        }

        if ( !$delete = $data->delete() ) {
            return response()->json(['error' => 'usuário não deletado', 500]);
        }

        return response()->json(['response' => $delete]);
    }
}
