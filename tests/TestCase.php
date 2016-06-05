<?php

namespace Tests;

use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Tymon\JWTAuth\JWTAuth;

abstract class TestCase extends LaravelTestCase
{
    use DatabaseMigrations;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var User
     */
    protected $authenticatedUser;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function jsonJWT($method, $uri, $data = [], $headers = [])
    {
        if ( ! $this->authenticatedUser) {
            $this->authenticatedUser = factory(User::class)->create();
        }

        $auth = $this->app->make(JWTAuth::class);
        $authorization = ['Authorization' => 'Bearer ' . $auth->fromUser($this->authenticatedUser)];

        parent::json($method, $uri, $data, $headers + $authorization);
    }

    public function see($data, $negate = false)
    {
        $this->assertJson(
            $this->response->getContent(), "JSON was not returned from [{$this->currentUri}]."
        );

        return $this->seeJson($data, $negate);
    }
}
