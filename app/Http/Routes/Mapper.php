<?php

namespace App\Http\Routes;

use Illuminate\Routing\Router;

abstract class Mapper implements MapperContract
{
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Mapper constructor.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $resource
     * @param string $controller
     * @param array  $options
     */
    protected function resource($resource, $controller, $options = [])
    {
        $defaults = ['only' => ['index', 'show', 'store', 'update', 'destroy']];

        $this->router->resource($resource, $controller, $options + $defaults);
    }
}
