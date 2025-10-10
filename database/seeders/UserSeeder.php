<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->create([
            'username' => 'owner',
            'fullname' => 'Owner Zofuta',
            'password' => Hash::make('owner'),
            'email' => 'dev@gmail.com',
            'notelp' => '083808380838',
            'alamat' => 'Jakarta',
            'role_id' => '1'
        ]);
        User::factory()->create([
            'username' => 'admin',
            'fullname' => 'Admin Lapangan',
            'password' => Hash::make('admin'),
            'email' => 'admin@gmail.com',
            'notelp' => '083808380838',
            'alamat' => 'Jakarta',
            'role_id' => '2'
        ]);
        User::factory()->create([
            'username' => 'user',
            'fullname' => 'User 1',
            'password' => Hash::make('user'),
            'email' => 'user@gmail.com',
            'notelp' => '083808380838',
            'alamat' => 'Jakarta',
            'role_id' => '3'
        ]);
    }
}
