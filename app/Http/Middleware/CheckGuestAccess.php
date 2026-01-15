<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CheckGuestAccess
{
    protected int $guestLimitPerDay = 2;
    protected int $registeredIpLimitPerDay = 2;

    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $date = now()->toDateString();

        // 获取或设置 guest_token
        $cookieToken = $request->cookie('guest_token');
        if (!$cookieToken) {
            $cookieToken = (string) Str::uuid();
            Cookie::queue(cookie('guest_token', $cookieToken, 60 * 24 * 30, '/', null, true, true, false, 'Lax'));
        }

        // 检查该 IP 是否为注册用户曾使用
        $isRegisteredUserIp = DB::table('ip_addresses')->where('ip_address', $ip)->exists();

        // 登录用户：自动记录 IP
        if (Auth::check()) {
            $userId = Auth::id();
            $existsForUser = DB::table('ip_addresses')
                ->where('user_id', $userId)
                ->where('ip_address', $ip)
                ->exists();

            if (!$existsForUser) {
                DB::table('ip_addresses')->insert([
                    'user_id' => $userId,
                    'ip_address' => $ip,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $next($request); // 登录用户不受限
        }

        // 限制：IP 属于注册用户 → 限制该 IP 的匿名访问频次
        if ($isRegisteredUserIp) {
            $ipCount = DB::table('guest_access_logs')
                ->where('ip_address', $ip)
                ->whereDate('access_date', $date)
                ->count();

            if ($ipCount >= $this->registeredIpLimitPerDay) {
                $request->attributes->set('guest_limit_exceeded', true);
                View::share('guest_limit_exceeded', true); // 用于前端判断是否受限
            } else {
                View::share('guest_limit_exceeded', false);
            }
        }

        // 限制：IP + Token 的组合，防止同 IP 不同浏览器反复访问
        $tokenCount = DB::table('guest_access_logs')
            ->where('ip_address', $ip)
            ->whereDate('access_date', $date)
            ->count();

        if ($tokenCount >= $this->guestLimitPerDay) {
            $request->attributes->set('guest_limit_exceeded', true);
            View::share('guest_limit_exceeded', true); // 用于前端判断是否受限
        } else {
            View::share('guest_limit_exceeded', false);
        }

        return $next($request);
    }
}

