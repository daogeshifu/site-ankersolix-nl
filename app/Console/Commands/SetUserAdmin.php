<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:set-admin
                            {user : 用户ID或邮箱}
                            {--remove : 移除管理员角色}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置或移除用户的管理员角色';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userIdentifier = $this->argument('user');
        $remove = $this->option('remove');

        // 查找用户（支持ID或邮箱）
        $user = is_numeric($userIdentifier)
            ? User::find($userIdentifier)
            : User::where('email', $userIdentifier)->first();

        if (!$user) {
            $this->error("用户不存在: {$userIdentifier}");
            return 1;
        }

        $this->info("找到用户: {$user->name} ({$user->email})");

        if ($remove) {
            // 移除管理员角色
            if ($user->hasRole('admin')) {
                $user->removeRole('admin');
                $this->info("已移除用户 {$user->name} 的管理员角色");
            } else {
                $this->warn("用户 {$user->name} 不是管理员");
            }
        } else {
            // 添加管理员角色
            if ($user->hasRole('admin')) {
                $this->warn("用户 {$user->name} 已经是管理员");
            } else {
                $user->assignRole('admin');
                $this->info("已将用户 {$user->name} 设置为管理员");
            }
        }

        // 显示用户当前角色
        $roles = $user->getRoleNames()->implode(', ') ?: '无';
        $this->info("当前角色: {$roles}");

        return 0;
    }
}
