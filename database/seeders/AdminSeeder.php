<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@kcca.go.ug'],
            [
                'name'         => 'KCCA Administrator',
                'password'     => Hash::make('Admin@1234'),
                'phone'        => '+256 414 234 000',
                'organization' => 'Kampala Capital City Authority',
                'role'         => 'admin',
            ]
        );
    }
}
