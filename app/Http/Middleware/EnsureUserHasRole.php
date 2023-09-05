<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $allRoles =
         [
            '1' => 'admin',
            '2' => 'librarian',
            '3' => 'user',
         ];


        foreach ($roles as $key => $role) {
            $kk = array_search($role, $allRoles);
            if ($kk) {
                $roles[$key] = $kk;
            } else {
                throw new \Exception("Роли $role не существует");
            }
        }

        if (!in_array($request->user()->role, $roles)) throw new NotFoundHttpException('Not Found');

        return $next($request);
    }
}
