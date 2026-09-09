<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.default_email');
        $password = config('admin.default_password');

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => config('admin.default_name'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $this->command?->info("默认管理员已就绪：{$email}");
    }
}
