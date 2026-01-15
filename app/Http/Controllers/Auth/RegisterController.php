<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivateAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * 显示注册页面
     */
    public function showRegistrationForm(Request $request)
    {
        return view('auth.register');
    }

    /**
     * 注册
     */
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $activationToken = Str::random(60);

    //     $user = User::create([
    //         'name' => $request->first_name . ' ' . $request->last_name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'remember_token' => $activationToken
    //     ]);

    //     Mail::to($user->email)->send(new ActivateAccount($user, $activationToken));     

    //     return redirect()->route('login')->with('success', 'Registration is successful, please log in to the mailbox to view the activation email.');
    // }

    /**
     * 注册
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'captcha'    => 'required|captcha',
        ], [
            'captcha.required' => __('lang.custom.captcha.required'),
            'captcha.captcha' => __('lang.custom.captcha.captcha'),
        ]);
    
        $activationToken = Str::random(60);
    
        $user = User::create([
            'name'           => $request->first_name . ' ' . $request->last_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'remember_token' => $activationToken,
        ]);

        //注册成功，则登录一下
        Auth::login($user);
    
        // Mail::to($user->email)->send(new ActivateAccount($user, $activationToken));
       
    
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful, please check your email for activation.',
                'redirect' => route('login'),
            ]);
        }
    
        return redirect()->route('login')
            ->with('success', 'Registration is successful, please log in to the mailbox to view the activation email.');
    }
    

    /**
     * 激活账号
     */
    public function activateAccount(Request $request, $token)
    {
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid activation token.');
        }

        // dd(date('Y-m-d H:i:s'), $user);

        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->remember_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Account activated successfully! You can now log in.');
    }
}
