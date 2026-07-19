<?php
// FILE: database/seeders/AdminSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name'     => 'Zaina Rahman',
                'email'    => 'zainarahman@gmail.com',
                'password' => 'zaina123',
            ],
            [
                'name'     => 'Suha Rahman',
                'email'    => 'suharahman632@gmail.com',
                'password' => 'suha123',
            ],
        ];

        foreach ($admins as $admin) {
            $user = User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name'         => $admin['name'],
                    'password'     => Hash::make($admin['password']),
                    'department'   => 'CSE',
                    'university'   => 'KUET',
                    'year'         => 4,
                    'availability' => 'busy', // admins aren't looking for a team
                ]
            );

            // is_admin is intentionally not mass-assignable (see User::$fillable),
            // so it's set explicitly here rather than passed through updateOrCreate.
            $user->is_admin = true;
            $user->email_verified_at = now();
            $user->save();
        }
    }
}