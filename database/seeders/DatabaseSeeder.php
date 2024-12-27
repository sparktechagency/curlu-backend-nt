<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory(10)->create();

          \App\Models\User::factory()->create([
              'name' => 'Admin',
              'last_name' => 'User',
              'email' => 'admin@gmail.com',
              'password' => bcrypt('1234567Rr'),
              'role_type' => 'ADMIN',
              'otp' => 0,
              'email_verified_at' => now(),
          ]);

         \App\Models\User::factory()->create([
             'name' => 'Professional',
             'last_name' => 'User',
             'email' => 'professional@gmail.com',
             'password' => bcrypt('1234567Rr'),
             'role_type' => 'PROFESSIONAL',
             'otp' => 0,
             'email_verified_at' => now(),
         ]);

       \App\Models\User::factory()->create([
           'name' => 'Regular',
           'last_name' => 'User',
           'email' => 'user@gmail.com',
           'password' => bcrypt('1234567Rr'),
           'role_type' => 'USER',
           'otp' => 0,
           'email_verified_at' => now(),
       ]);
    }
}
