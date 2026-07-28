<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  string[]  ...$guards
     *
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards): Response
    {
        $this->authenticate($request, $guards);

        App::setLocale(getLoggedInUser()->language);

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * Laravel stores the current URL as session url.intended via redirect()->guest().
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('employer') || $request->is('employer/*')) {
                return route('front.employee.login');
            }

            if ($request->is('candidate') || $request->is('candidate/*')) {
                return route('front.candidate.login');
            }

            return route('front.candidate.login');
        }
    }
}
