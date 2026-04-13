<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or Update Admin User
        User::updateOrCreate(
            ['email' => 'admin@kasir.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create or Update Employee User
        User::updateOrCreate(
            ['email' => 'kasir@kasir.com'],
            [
                'name' => 'Kasir Pusat',
                'password' => Hash::make('kasir123'),
                'role' => 'employee',
            ]
        );

        $this->command->info('✅ Admin and Employee users created successfully!');
        $this->command->info('📧 Admin: admin@kasir.com / admin123');
        $this->command->info('📧 Employee: kasir@kasir.com / kasir123');
    }
}
