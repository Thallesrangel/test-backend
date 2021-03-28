<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     ** path="/api/auth/login",
     *   tags={"Authentication"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Autenticado",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(response=401, description="Não autenticado"),
     *)
     **/
    
    public function login(Request $request)
    {
        // Validação
        $credentials = $request->only(['email', 'password']);
       
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Usuário ou senha incorreto.'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     ** path="/api/auth/logout",
     *   tags={"Authentication"},
     *   summary="Logout",
     *   operationId="logout",
     *   @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     *   ),
     *   @OA\Response(response=200, description="Deslogado com sucesso", @OA\MediaType( mediaType="application/json")),
     *   @OA\Response(response=401, description="Não autenticado"),
     *   @OA\Response(response=422, description="Informe o token"),
     *)
     **/

    public function logout(Request $request)
    {
        if (empty($request->header('Authorization'))) {
            throw ValidationException::withMessages(['token_required' => 'Informe o token']);
        }

        auth('api')->logout();

        return response()->json(['message' => 'Usuário deslogado com sucesso.']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
