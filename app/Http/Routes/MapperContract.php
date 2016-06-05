<?php

namespace App\Http\Routes;

interface MapperContract
{
    /**
     * Prepare the route model bindings.
     *
     * @return void
     */
    public function boot();

    /**
     * Map the actual routes of the module.
     *
     * @return void
     */
    public function map();
}
