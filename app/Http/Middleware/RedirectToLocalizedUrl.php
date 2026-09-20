<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RedirectToLocalizedUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        if (session('languageName') !== 'bn' || ! $routeName || ! Route::has('bn.'.$routeName)) {
            return $next($request);
        }

        $path = ltrim($request->path(), '/');
        $url = url('/bn'.($path === '' ? '/' : '/'.$path));

        if ($request->getQueryString()) {
            $url .= '?'.$request->getQueryString();
        }

        return redirect()->to($url, 302);
    }
}
