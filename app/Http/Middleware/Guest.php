<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class Guest
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    private $auth;

    /**
     * Guest constructor.
     *
     * @param \Tymon\JWTAuth\JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $this->auth->setRequest($request)->getToken();
        if ($token !== false) {
            return response()->json(null, 401);
        }

        return $next($request);
    }
}
