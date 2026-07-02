<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@sahem.org'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('f8e0aeffbc2761847549e99adbb9f892'),
                'is_admin' => true,
                'role' => 'super_admin',
                'is_active' => true,
                'preferred_locale' => 'ar',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@etelafrelief.org'],
            [
                'name' => 'Admin',
                'password' => Hash::make('bdeeadd3b95005bd93af32d389102937'),
                'is_admin' => true,
                'role' => 'super_admin',
                'is_active' => true,
                'preferred_locale' => 'en',
            ]
        );
    }
}
