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

        // Seed profile images from assets for demo
        $guest1Path = public_path('assets/guest1.jpg');
        $guest1Data = file_exists($guest1Path) ? file_get_contents($guest1Path) : null;
        $guest1Mime = $guest1Data ? 'image/jpeg' : null;

        $guest2Path = public_path('assets/guest2.jpg');
        $guest2Data = file_exists($guest2Path) ? file_get_contents($guest2Path) : null;
        $guest2Mime = $guest2Data ? 'image/jpeg' : null;

        // Buyer
        $buyer = User::updateOrCreate(
            ['email' => 'buyer@gmail.com'],
            [
                'name' => 'Art Collector',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'email_verified_at' => now(),
                'profile_image_blob' => $guest1Data,
                'profile_image_mime' => $guest1Mime,
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
                'profile_image_blob' => $guest2Data,
                'profile_image_mime' => $guest2Mime,
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
