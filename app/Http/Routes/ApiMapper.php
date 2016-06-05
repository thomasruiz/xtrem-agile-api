<?php

namespace App\Http\Routes;

use App\Models\Project;
use App\Models\Story;
use App\Models\User;

class ApiMapper extends Mapper
{
    /**
     * Prepare the route model bindings.
     *
     * @return void
     */
    public function boot()
    {
        $this->router->model('projects', Project::class);
        $this->router->model('stories', Story::class);
        $this->router->model('users', User::class);
    }

    /**
     * Map the actual routes of the module.
     *
     * @return void
     */
    public function map()
    {
        $this->router->group(['middleware' => 'guest'], function () {
            $this->router->post('auth', 'AuthController@store');
            $this->resource('users', 'UsersController', ['only' => ['store']]);
        });

        $this->router->group(['middleware' => 'auth'], function () {
            $this->resource('users', 'UsersController', ['only' => ['show']]);
            $this->resource('projects', 'ProjectsController', ['only' => ['store']]);

            $this->router->group(['middleware' => 'can:update,users'], function () {
                $this->resource('users', 'UsersController', ['only' => ['update', 'destroy']]);
            });

            $this->router->group(['middleware' => 'can:access,stories'], function () {
                $this->resource('stories', 'StoriesController', ['only' => ['show', 'update', 'destroy']]);
            });

            $this->router->group(['middleware' => 'can:access,projects'], function () {
                $this->resource('projects', 'ProjectsController', ['only' => ['show', 'update', 'destroy']]);
                $this->resource('projects.users', 'ProjectsUsersController', ['only' => ['update', 'destroy']]);
                $this->resource('projects.stories', 'ProjectsStoriesController', ['only' => ['index', 'store']]);
            });
        });
    }
}
