<?php

namespace App\Http\Controllers;

use App\Http\Requests\Projects\StoreRequest;
use App\Models\Project;
use Tymon\JWTAuth\JWTAuth;

class ProjectsController extends Controller
{
    public function show(Project $project)
    {
        return response()->json(compact('project'));
    }

    public function store(StoreRequest $request, JWTAuth $auth)
    {
        $project = Project::create($request->all());
        $project->users()->save($auth->toUser());

        return response()->json(compact('project'), 201);
    }
}
