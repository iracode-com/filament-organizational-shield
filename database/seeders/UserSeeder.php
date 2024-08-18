<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name'          => 'John Dow',
            'email'         => 'payesh@admin.com',
            'role'          => UserRole::ADMIN,
            'national_code' => 1111111111,
            'status'        => true
        ]);
        $user->assignRole('super_admin');

        User::factory()->count(5)->create();
    }
}
