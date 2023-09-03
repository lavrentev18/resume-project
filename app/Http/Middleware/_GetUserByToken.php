<?php

namespace App\Http\Middleware;

use Laravel\Sanctum\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GetUserByToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {

       //return \Response::json($token);
        $request->setUserResolver(function () use ($request) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            return $token->tokenable()->first();
        });
       //return \Response::json($token->tokenable()->first());
        return $next($request);

    }
}
