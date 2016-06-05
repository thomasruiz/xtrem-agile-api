<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\User;

class UsersController extends Controller
{
    public function show(User $user)
    {
        return response()->json(compact('user'));
    }

    public function store(StoreRequest $request)
    {
        $user = new User($request->all());
        $user->password = $request->input('password');
        $user->save();

        return response()->json(compact('user'), 201);
    }

    public function update(User $user, UpdateRequest $request)
    {
        $this->authorize('update', $user);
        
        $user->update($request->all());

        return response()->json(compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('update', $user);

        $user->delete();

        return response(null, 204);
    }
}
