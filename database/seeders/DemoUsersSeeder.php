<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password'), // badili baadaye
            ]
        );
        $admin->assignRole('admin');

        // Teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name'     => 'Demo Teacher',
                'password' => Hash::make('password'),
            ]
        );
        $teacher->assignRole('teacher');

        // Parent
        $parent = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name'     => 'Demo Parent',
                'password' => Hash::make('password'),
            ]
        );
        $parent->assignRole('parent');

        // Academic Officer
        $academic = User::firstOrCreate(
            ['email' => 'academic@example.com'],
            [
                'name'     => 'Academic Officer',
                'password' => Hash::make('password'),
            ]
        );
        $academic->assignRole('academic');
    }
}
