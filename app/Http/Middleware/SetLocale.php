<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the locale from the URL if it exists
        $locale = $request->segment(1);

        // Check if the locale is valid from URL
        if (in_array($locale, array_keys(LaravelLocalization::getSupportedLocales()))) {
            App::setLocale($locale);
            session(['locale' => $locale]); // Store in session
        } else {
            // Try to get locale from session
            $sessionLocale = session('locale');

            if ($sessionLocale && in_array($sessionLocale, array_keys(LaravelLocalization::getSupportedLocales()))) {
                App::setLocale($sessionLocale);
            } else {
                // Set default locale (English)
                $defaultLocale = config('laravellocalization.defaultLocale', 'en');
                App::setLocale($defaultLocale);
                session(['locale' => $defaultLocale]);
            }
        }

        return $next($request);
    }
} 