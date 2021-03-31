<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function headers($user = null) {
        $headers = ['Accept' => 'application/json'];
        
        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer'.$token;
        }
      
        return $headers;
    }

    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/user', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["Campo nome é obrigatório"],
                    "id_user_category" => ["Categoria de usuário é obrigatório"],
                    "document" => ["Campo documento é obrigatório"],
                    "email" => ["Campo email é obrigatório"],
                    "password" => ["Campo senha é obrigatório"],
                ]
            ]);
    }

    public function test_created_user_through_route()
    {
        $data = [
            'name' => 'simple example',
            'id_user_category' => '1',
            'document' => 'simple',
            'email' => 'simple@example.com',
            'password' => 'simple123'
        ];
        
        $response = $this->postJson('/api/user', $data);
        $response->assertStatus(201);
    }

    public function test_return_information_of_user() 
    {
        factory(User::class)->create([
            'name' => 'Test',
            'document' => '123',
            'email' => 'test@gmail.com',
            'id_user_category' => '1',
            'password' => 'test123',
        ]);
    
        $user = User::first();
   
        $test = $this->json('GET','/api/user/'. $user->id_user, [],$this->headers($user));
        $test->assertStatus(200);
    }

    public function test_user_update()
    {
        factory(User::class)->create([
            'name' => 'Test Example',
            'document' => '123',
            'email' => 'example@test.com',
            'id_user_category' => '1',
            'password' => 'example123',
        ]);
    
        $user = User::first();
        $user->id_user_category = 2;
        $user->save();
    
        $response = $this->json('GET','/api/user/', [], $this->headers($user));
        $response->assertStatus(200);
    }
}
