<?php

namespace App\Policies;

use App\Models\Story;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoryPolicy
{
    use HandlesAuthorization;

    public function access(User $user, Story $story)
    {
        return $user->projects()->find($story->project_id) !== null;
    }
}
