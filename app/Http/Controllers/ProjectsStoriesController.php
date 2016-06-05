<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stories\StoreRequest;
use App\Models\Project;

class ProjectsStoriesController extends Controller
{
    public function index(Project $project)
    {
        $stories = $project->stories;

        return response()->json(compact('stories'));
    }

    public function store(Project $project, StoreRequest $request)
    {
        $story = $project->stories()->create($request->all());

        return response()->json(compact('story'), 201);
    }
}
