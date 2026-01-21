<?php

namespace Database\Seeders;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArtworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artist = User::where('email', 'artist@gmail.com')->first();

        if (!$artist) {
            $this->command->error('Artist user not found. Please run UserSeeder first.');
            return;
        }

        $statuses = array_merge(
            array_fill(0, 3, 'pending'),
            array_fill(0, 12, 'approved'),
            array_fill(0, 2, 'rejected'),
            array_fill(0, 6, 'sold'),
            array_fill(0, 1, 'approved') // Remaining 1
        );

        shuffle($statuses); // Optional: Randomize where which image goes, or keep it systematic.
        // Actually, user might prefer systematic distribution for testing. Not shuffling for now.

        for ($i = 1; $i <= 24; $i++) {
            Artwork::updateOrCreate(
                ['title' => 'Artwork ' . $i, 'user_id' => $artist->id],
                [
                    'description' => 'A unique masterpiece captured on canvas, showcasing the beauty of artistic expression. This piece is part of the exclusive Master Artist collection.',
                    'price' => rand(150, 450),
                    'image_path' => 'Artworks/a' . $i . '.jpg',
                    'status' => $statuses[$i - 1],
                ]
            );
        }
    }
}
