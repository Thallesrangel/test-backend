<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testRequiredFieldsForLogin()
    {
        $this->json('POST', 'api/auth/login', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["Campo email é obrigatório"],
                    "password" => ["Campo senha é obrigatório"],
                ]
            ]);
    }
}
