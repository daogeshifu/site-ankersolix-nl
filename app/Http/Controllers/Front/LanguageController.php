<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change the application language.
     *
     * @param  Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang(Request $request, $locale)
    {
        // Check if the locale exists in our supported languages
        $supportedLocales = config('laravellocalization.supportedLocales');

        if (array_key_exists($locale, $supportedLocales)) {
            // Store locale in session
            Session::put('locale', $locale);
            App::setLocale($locale);

            // Get the previous URL
            $previousUrl = url()->previous();

            // Get the current URL path without domain
            $path = parse_url($previousUrl, PHP_URL_PATH);

            // Remove the old locale prefix if exists
            foreach (array_keys($supportedLocales) as $loc) {
                if (strpos($path, '/' . $loc . '/') === 0) {
                    $path = substr($path, strlen('/' . $loc));
                    break;
                } elseif ($path === '/' . $loc) {
                    $path = '/';
                    break;
                }
            }

            // Build new URL with new locale prefix
            // If locale is the default locale, don't add prefix due to hideDefaultLocaleInURL setting.
            if ($locale === config('laravellocalization.defaultLocale', 'nl')) {
                $newUrl = $path;
            } else {
                $newUrl = '/' . $locale . $path;
            }

            return redirect($newUrl);
        }

        return redirect()->back();
    }
} 
