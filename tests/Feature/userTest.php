<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class userTest extends TestCase
{
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
}
