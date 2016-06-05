<?php

namespace App\Providers;

use App\Http\Routes\ApiMapper;
use App\Http\Routes\MapperContract;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * @var MapperContract[]
     */
    protected $mappers = [];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->mappers = [
            new ApiMapper($router),
        ];

        foreach ($this->mappers as $mapper) {
            $mapper->boot();
        }

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'api',
        ], function () {
            foreach ($this->mappers as $mapper) {
                $mapper->map();
            }
        });
    }
}
