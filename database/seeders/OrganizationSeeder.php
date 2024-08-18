<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::query()->create([
            'name' => 'پایش',
            'slug' => str('payesh')->slug()->append(Str::random(5))->value(),
            'copyright' => 'تمام حقوق مادی و معنوی این سایت متعلق به معاونت حفاظت فناوری اطلاعات (IT) حراست کل جمعیت هلال احمر جمهوری اسلامی ایران میباشد.'
        ]);
    }
}
