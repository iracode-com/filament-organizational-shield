<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\FilamentShield;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShieldSeeder::class,
            UserSeeder::class,
            OrganizationSeeder::class,
            PositionSeeder::class,
        ]);

    }
}
