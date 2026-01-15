<?php

use Illuminate\Support\Facades\Route;

// ===============================================
// 用户后台路由 (需要登录认证)
// ===============================================
Route::middleware('auth')->prefix('user_admin')->group(function () {
    // 首页和用户信息
    Route::get('/', [\App\Http\Controllers\UserAdmin\IndexController::class, 'index'])->name('user_admin.index');
    Route::get('user_info', [\App\Http\Controllers\UserAdmin\UserController::class, 'user_info'])->name('user_admin.user_info');

    // 用户设置更新
    Route::post('/update-user-avatar-setting', [\App\Http\Controllers\UserAdmin\UserController::class, 'updateUserAvatar'])->name('user.updateSettingAvatar');
    Route::post('update-user-setting', [\App\Http\Controllers\UserAdmin\UserController::class, 'updateUserInfo'])->name('user.updateUserSettingInfo');
    Route::post('/update-user-profile', [\App\Http\Controllers\UserAdmin\UserController::class, 'updateUserProfile'])->name('user.updateUserProfile');

    // 用户金额记录
    Route::get('user-record-amounts', [\App\Http\Controllers\UserAdmin\UserController::class, 'userRecordAmounts'])->name('user-record-amounts');
});
