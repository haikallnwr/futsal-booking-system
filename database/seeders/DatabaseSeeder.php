<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            GorSeeder::class,
            FieldSeeder::class,
            ScheduleSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class
        ]);
    }
}
