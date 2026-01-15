<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * 注册
     */
    public function register(Request $request)
    {
        return view('admin_user.register');
    }

    /**
     * 注册
     */
    public function registerPost(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin_users',
            'password' => 'required|string|min:6',
        ]);

        $user = AdminUser::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')->with('success', '注册成功,请登录！');
    }

    /**
     * 登录
     */
    public function login(Request $request)
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = AdminUser::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::guard('admin')->login($user);
            $request->session()->regenerate();
            return redirect()->route('admin.article.create')->with('success', '登录成功！');
        }

        return back()->withErrors([
            'email' => '邮箱或密码错误',
        ])->withInput();
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', '退出成功！');
    }

    /**
     * 用户列表
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::query();

        // 搜索功能
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.user.index', compact('users'));
    }

    /**
     * 每日注册趋势数据
     */
    public function registrationTrend(Request $request)
    {
        $days = $request->input('days', 30); // 默认显示30天

        $data = \App\Models\User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 填充缺失的日期
        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $data->firstWhere('date', $date)?->count ?? 0;
            $result[] = [
                'date' => $date,
                'count' => $count
            ];
        }

        return response()->json($result);
    }


}
