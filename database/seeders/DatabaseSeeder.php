<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@mail.com',
            'password' => Hash::make(config('auth.admin.password')),
        ]);

        $this->call(RoleSeeder::class);
        $this->call(UserRoleSeeder::class);
    }
}
