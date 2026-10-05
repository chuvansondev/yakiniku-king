<?php

namespace App\Http\Middleware;

use App\Enums\AppLocale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', AppLocale::Vietnamese->value);

        if (! in_array($locale, array_map(fn (AppLocale $locale): string => $locale->value, AppLocale::cases()), true)) {
            $locale = AppLocale::Vietnamese->value;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
