<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response as HttpResponse;

class SimpleAuthenticate 
{
    protected $auth;

    public function __construct(Guard $auth)
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
        if ($request->headers->has('X-UserId')) {
            $this->auth->once(['id' => (int) $request->header('X-UserId')]);
        }
        if ($this->auth->guest()) {
            return response(null, HttpResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
