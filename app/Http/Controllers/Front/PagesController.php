<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * 隐私政策页面
     */
    public function policy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * 服务条款页面
     */
    public function terms()
    {
        return view('pages.terms-of-use');
    }
}
