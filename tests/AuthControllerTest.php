<?php

namespace Tests;

use App\Models\User;

class AuthControllerTest extends TestCase
{
    public function testGetAJWT()
    {
        $user = factory(User::class)->create(['password' => 'password']);

        $this->json('post', '/auth', ['email' => $user->email, 'password' => 'password']);

        $this->assertResponseOk();
        $this->seeJsonStructure(['token']);
    }
}
