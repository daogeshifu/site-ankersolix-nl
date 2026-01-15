<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * 首页
     */
    public function index(Request $request)
    {
        return view('index.index', [
            'navbar' => 'index'
        ]);
    }
}
