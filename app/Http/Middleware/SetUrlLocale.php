<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetUrlLocale
{
    public function handle(Request $request, Closure $next, string $locale): Response
    {
        abort_unless(in_array($locale, ['en', 'bn'], true), 404);

        App::setLocale($locale);
        Session::put('languageName', $locale);

        return $next($request);
    }
}
