<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'               => 'Admin',
            'email'              => 'admin@admin.com',
            'password'           => bcrypt('admin123'),
            'role'               => 'admin',
            'email_verified_at'  => now(),
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}