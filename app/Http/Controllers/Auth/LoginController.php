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
        // 验证字段，包括验证码
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required', 'captcha'],
        ], [
            'captcha.required' => __('lang.custom.captcha.required'),
            'captcha.captcha' => __('lang.custom.captcha.captcha'),
        ]);
    
        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('index')
                ]);
            }
            return redirect()->route('index');
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
        return redirect()->route('index');
    }
}
