<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;

class ProjectsUsersController extends Controller
{
    public function update(Project $project, User $user)
    {
        if ($project->users()->find($user->id)) {
            return response()->json(['user' => ['This user already has access to this project.']], 400);
        }

        $project->users()->save($user);

        return response(null, 204);
    }

    public function destroy(Project $project, User $user)
    {
        $project->users()->detach($user->id);

        return response(null, 204);
    }
}
