<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 清空缓存
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 创建管理员角色
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );

        // 创建普通用户角色
        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['guard_name' => 'web']
        );

        // 将用户 ID 为 1 的用户设置为管理员
        $user = User::find(1);
        if ($user) {
            // 移除所有现有角色
            $user->syncRoles([]);
            // 分配管理员角色
            $user->assignRole('admin');

            $this->command->info('用户 ID 1 已被设置为管理员');
        } else {
            $this->command->warn('用户 ID 1 不存在');
        }

        $this->command->info('角色创建完成：');
        $this->command->info('- 管理员 (admin)');
        $this->command->info('- 普通用户 (user)');
    }
}
