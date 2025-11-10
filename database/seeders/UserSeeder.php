<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'user_type' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('Test@123!!'),
            ],
            // [
            //     'first_name' => 'Jane',
            //     'last_name' => 'Smith',
            //     'user_type' => '',
            //     'email' => 'jane.smith@example.com',
            //     'password' => bcrypt('Test@123!!'),
            // ],
        ];

        foreach ($users as $user) {
            $savedUser = User::create($user);
            $savedUser->assignRole($user['user_type']);
        }
    }
}