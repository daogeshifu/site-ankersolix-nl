<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;  // ✅ 一定要是这个

class VerifyCsrfToken extends Middleware
{

    

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'paypal/webhook',
        '/user_admin/update-user-avatar-setting',
        '/user_admin/user_admin.detector.save_text',
        '/user_admin/start_detecting',
        '/user_admin/user_admin.reduce.save_text',
        '/user_admin/reduce.start_reducing',
        '/front/fileUpload',
    ];


    public function handle($request, Closure $next)
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
