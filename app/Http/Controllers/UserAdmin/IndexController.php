<?php

namespace App\Http\Controllers\UserAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\UserAmountLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

class IndexController extends Controller
{
    //
    public function index()
    {

        //获取用户信息
        $user = Auth::user();
        // 获取 最新的 5 片文章
        $articles = Article::orderBy('id', 'desc')->take(5)->get();
        //获取用户电话
        return view('user_admin.index.index', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }




}
