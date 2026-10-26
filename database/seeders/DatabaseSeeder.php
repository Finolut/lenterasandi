<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin utama Lentera Sandi — Bidang Persandian Diskominfo Jatim
        User::updateOrCreate(
            ['email' => 'admin@jatimprov.go.id'],
            [
                'name'              => 'Admin Lentera Sandi',
                'password'          => Hash::make('LenteraSandi@2025'),
                'email_verified_at' => now(),
            ]
        );
    }
}
