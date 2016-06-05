<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stories\StoreRequest;
use App\Http\Requests\Stories\UpdateRequest;
use App\Models\Project;
use App\Models\Story;

class StoriesController extends Controller
{
    public function show(Story $story)
    {
        return response()->json(compact('story'));
    }

    public function update(Story $story, UpdateRequest $request)
    {
        $story->update($request->all());

        return response()->json(compact('story'));
    }

    public function destroy(Story $story)
    {
        $story->delete();

        return response(null, 204);
    }
}
