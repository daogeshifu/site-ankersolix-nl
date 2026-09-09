<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * 显示登录页面
     */
    public function showLoginForm(Request $request)
    {
        return view('auth.login');
    }
    
    /**
     * 登录
     */
    // public function login(Request $request)
    // {
    //     $params = $request->all();
    //     if (Auth::attempt(['email' => $params['email'], 'password' => $params['password']])) {
    //         return redirect()->route('index');
    //     }
    //     return redirect()->back()->with('error', 'User name or password error.');   
    // }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();
            $redirect = $request->user()->hasRole('admin')
                ? route('admin.index')
                : route('index');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $redirect,
                ]);
            }

            return redirect()->intended($redirect);
        }
    
        if ($request->ajax()) {
            // AJAX请求登录失败，返回json错误
            return response()->json([
                'success' => false,
                'message' => '邮箱或密码错误',
            ], 401);
        }
    
        // 普通请求登录失败，返回带错误的重定向
        return redirect()->back()->withInput()->with('error', '邮箱或密码错误');
    }
    

    
    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }
}
