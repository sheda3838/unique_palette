<?php

namespace Database\Seeders;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyer = User::where('email', 'buyer@gmail.com')->first();
        $soldArtworks = Artwork::where('status', 'sold')->get();

        if (!$buyer || $soldArtworks->count() < 6) {
            $this->command->error('Buyer not found or insufficient sold artworks. Please run UserSeeder and ArtworkSeeder first.');
            return;
        }

        // Create 3 orders, each with 2 sold artworks
        for ($i = 0; $i < 3; $i++) {
            $artworksForOrder = $soldArtworks->slice($i * 2, 2);
            $totalAmount = $artworksForOrder->sum('price');
            $paymentId = 'PAY-SEED-00' . ($i + 1);

            $order = Order::updateOrCreate(
                ['payment_id' => $paymentId],
                [
                    'user_id' => $buyer->id,
                    'total_amount' => $totalAmount,
                    'status' => 'completed',
                ]
            );

            foreach ($artworksForOrder as $artwork) {
                OrderItem::updateOrCreate(
                    ['order_id' => $order->id, 'artwork_id' => $artwork->id],
                    [
                        'price' => $artwork->price,
                    ]
                );
            }
        }
    }
}
