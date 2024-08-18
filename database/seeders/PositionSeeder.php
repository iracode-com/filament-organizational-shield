<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'حسابدار'],
            ['name' => 'سرپرست'],
            ['name' => 'کارشناس امور اداری'],
            ['name' => 'کارشناس پشتیبانی'],
            ['name' => 'کارشناس تولید محتوا'],
            ['name' => 'کارشناس فروش'],
            ['name' => 'کارمند'],
            ['name' => 'مدیر عامل'],
            ['name' => 'مدیر مالی'],
            ['name' => 'معاون'],
        ];

        Arr::map($positions, function ($position) {
            Position::query()->create($position);
        });
    }
}
