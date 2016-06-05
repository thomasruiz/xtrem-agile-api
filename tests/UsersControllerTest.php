<?php

namespace Tests;

use App\Models\User;

class UsersControllerTest extends TestCase
{
    public function testRetrieveAUser()
    {
        $user = factory(User::class)->create();

        $this->jsonJWT('get', '/users/1');

        $this->assertResponseOk();
        $this->see(['user' => ['id' => 1, 'email' => $user->email, 'name' => $user->name]]);
    }

    public function testRetrieveAUserWhenNotAuthenticated()
    {
        factory(User::class)->create();

        $this->json('get', '/users/1');

        $this->assertResponseStatus(400);
    }

    public function testCreateANewUser()
    {
        $this->json('post', '/users', [
            'name'                  => $name = $this->faker->name,
            'email'                 => $email = $this->faker->email,
            'password'              => $password = $this->faker->password,
            'password_confirmation' => $password,
        ]);

        $this->assertResponseStatus(201);
        $this->see(['user' => ['id' => 1, 'email' => $email, 'name' => $name]]);
        $this->seeInDatabase('users', ['id' => 1, 'email' => $email, 'name' => $name]);
    }

    public function testCreateAUserWithoutTheRequiredFields()
    {
        $this->json('post', '/users', []);

        $this->assertResponseStatus(422);
        $this->seeJsonStructure(['name', 'email', 'password']);
    }

    public function testCreateAUserWithAShortPassword()
    {
        $this->json('post', '/users', [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->email,
            'password'              => $password = $this->faker->randomLetter,
            'password_confirmation' => $password,
        ]);

        $this->assertResponseStatus(422);
        $this->seeJsonStructure(['password']);
    }

    public function testCreateAUserWithAWrongPasswordConfirmation()
    {
        $this->json('post', '/users', [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->email,
            'password'              => $this->faker->password,
            'password_confirmation' => $this->faker->password,
        ]);

        $this->assertResponseStatus(422);
        $this->seeJsonStructure(['password']);
    }

    public function testCreateAUserWhenAuthenticated()
    {
        $this->jsonJWT('post', '/users', [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->email,
            'password'              => $password = $this->faker->words(3, true),
            'password_confirmation' => $password,
        ]);

        $this->assertResponseStatus(401);
    }

    public function testUpdateAUser()
    {
        $this->jsonJWT('put', '/users/1', [
            'name'  => $name = $this->faker->name,
            'email' => $email = $this->faker->email,
        ]);

        $this->assertResponseOk();
        $this->see(['user' => ['id' => 1, 'email' => $email, 'name' => $name]]);
    }

    public function testUpdateAUserWithoutRequiredFields()
    {
        $this->jsonJWT('put', '/users/1', []);

        $this->assertResponseStatus(422);
        $this->seeJsonStructure(['name', 'email']);
    }

    public function testUpdateAUserWithAnExistingEmail()
    {
        $user = factory(User::class)->create();

        $this->jsonJWT('put', '/users/2', ['email' => $user->email]);

        $this->assertResponseStatus(422);
        $this->seeJsonStructure(['email']);
    }

    public function testUpdateAUsersPassword()
    {
        $this->jsonJWT('put', '/users/1', [
            'password'              => $password = $this->faker->password,
            'password_confirmation' => $password,
        ]);

        $this->assertResponseOk();
    }

    public function testUpdateAUserWhenNotAuthenticated()
    {
        factory(User::class)->create();

        $this->json('put', '/users/1', []);

        $this->assertResponseStatus(400);
    }

    public function testUpdateADifferentUser()
    {
        $user = factory(User::class)->create();

        $this->jsonJWT('put', '/users/' . $user->id, []);

        $this->assertResponseStatus(403);
    }

    public function testDestroyAUser()
    {
        $this->jsonJWT('delete', '/users/1');

        $this->assertResponseStatus(204);
    }

    public function testDestroyAUserWhenNotAuthenticated()
    {
        factory(User::class)->create();

        $this->json('delete', '/users/1');

        $this->assertResponseStatus(400);
    }

    public function testDestroyADifferentUser()
    {
        factory(User::class)->create();

        $this->jsonJWT('delete', '/users/1');

        $this->assertResponseStatus(403);
    }
}
