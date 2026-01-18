<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\BankDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Buyer
        $buyer = User::updateOrCreate(
            ['email' => 'buyer@gmail.com'],
            [
                'name' => 'Art Collector',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'email_verified_at' => now(),
            ]
        );

        // Seed Buyer Address (Onboarding)
        Address::updateOrCreate(
            ['user_id' => $buyer->id],
            [
                'province' => 'Western',
                'city' => 'Colombo',
                'street' => '123 Art Avenue',
                'postal_code' => '00100',
            ]
        );

        // Artist
        $artist = User::updateOrCreate(
            ['email' => 'artist@gmail.com'],
            [
                'name' => 'Master Artist',
                'password' => Hash::make('password'),
                'role' => 'artist',
                'email_verified_at' => now(),
            ]
        );

        // Seed Artist Address (Onboarding)
        Address::updateOrCreate(
            ['user_id' => $artist->id],
            [
                'province' => 'Central',
                'city' => 'Kandy',
                'street' => '456 Palette Lane',
                'postal_code' => '20000',
            ]
        );

        // Seed Artist Bank Details (Onboarding)
        BankDetail::updateOrCreate(
            ['user_id' => $artist->id],
            [
                'bank_name' => 'Demo Bank',
                'branch' => 'Main Branch',
                'account_name' => 'Master Artist',
                'account_number' => '1234567890',
            ]
        );
    }
}
