<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = Role::where('name', 'admin')->value('id');
        $userRoleId = Role::where('name', 'user')->value('id');

        User::firstOrCreate(
            ['email' => 'admin@bumimandiri.id'],
            [
                'role_id' => $adminRoleId,
                'name' => 'Admin Bumi Mandiri',
                'phone' => '081200000000',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $names = [
            ['Dewi Anggraini', 'dewi@example.com'],
            ['Rizky Pratama', 'rizky@example.com'],
            ['Siti Nurhaliza', 'siti@example.com'],
            ['Agus Setiawan', 'agus@example.com'],
            ['Putri Ayu Lestari', 'putri@example.com'],
        ];

        foreach ($names as [$name, $email]) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'role_id' => $userRoleId,
                    'name' => $name,
                    'phone' => '0812' . rand(10000000, 99999999),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
