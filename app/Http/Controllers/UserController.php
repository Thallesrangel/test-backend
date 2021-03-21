<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Model\User;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return UserResource::collection($user);
    }

    public function store(Request $request)
    {
    
    }

    public function show($id)
    {
    
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
