<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreRequest;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    public function store(StoreRequest $request, JWTAuth $auth)
    {
        $token = $auth->attempt($request->all());
        if ($token === false) {
            return response()->json(null, 401);
        }

        return response()->json(compact('token'));
    }
}
