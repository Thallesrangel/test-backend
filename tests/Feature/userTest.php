<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_created_user()
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
}
