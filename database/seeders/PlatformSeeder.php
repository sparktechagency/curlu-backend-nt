<?php

namespace Database\Seeders;

use App\Models\PlatformFee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlatformFee::create([
            'curlu_earning'=>5,
        ]);
    }
}
