<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $locale = array_filter($request->header(), function ($k) {
            return $k == 'locale';
        }, ARRAY_FILTER_USE_KEY);

        if ($locale) {
            switch ($locale["locale"][0]) {
                case 'en-US':
                    $request["locale"] = 'en';
                    break;
                case 'es-ES':
                    $request["locale"] = 'es';
                    break;
                default:
                    return response()->json(['status' => 'Fail',"message"=>"the language was not found"], 500);
                    break;
            }
        } else {
            return response()->json(['status' => 'Fail',"message"=>"the language was not found"], 500);
        }
        return $next($request);
    }
}
