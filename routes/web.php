<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Gallery\ArtGallery;
use App\Livewire\Onboarding;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Artist\Artworks as ArtistArtworks;
use App\Livewire\Artist\UploadArtwork;
use App\Livewire\Artist\EditArtwork;
use App\Livewire\Buyer\Orders as BuyerOrders;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Http\Controllers\StripeController;

/*
|--------------------------------------------------------------------------
| Web Routes - Standard Pages (Blade/Livewire)
|--------------------------------------------------------------------------
|
| All pages accessed via browser, rendered with Blade templates or
| Livewire components. Includes role-based protection.
|
*/

// Public Routes
Route::get('/', \App\Livewire\Home::class)->name('welcome');
Route::get('/gallery', ArtGallery::class)->name('gallery');
Route::get('/about-us', \App\Livewire\AboutUs::class)->name('about-us');
Route::get('/debug/mail-test', [\App\Http\Controllers\MailDebugController::class, 'test']);

// Image serving routes (BLOB)
Route::get('/artwork-image/{id}', [\App\Http\Controllers\ImageController::class, 'showArtwork'])->name('artwork.image');
Route::get('/users/{id}/profile-image', [\App\Http\Controllers\ImageController::class, 'showProfile'])->name('user.profile-image');

Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
});

// Social Authentication
Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sanctum/Session)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    \App\Http\Middleware\CheckOnboarding::class,
])->group(function () {

    Route::get('/onboarding', Onboarding::class)->name('onboarding');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user) return redirect()->route('welcome');

        $role = $user->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'artist') return redirect()->route('artist.artworks');
        return redirect()->route('welcome');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
        // Admin tabs as explicitly requested pages
        Route::get('/admin/users', AdminDashboard::class)->name('admin.users');
        Route::get('/admin/artworks', AdminDashboard::class)->name('admin.artworks');
        Route::get('/admin/orders', AdminDashboard::class)->name('admin.orders');
    });

    /*
    |--------------------------------------------------------------------------
    | Artist Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['artist'])->group(function () {
        Route::get('/artist/artworks', ArtistArtworks::class)->name('artist.artworks');
        Route::get('/artist/upload', UploadArtwork::class)->name('artist.upload');
        Route::get('/artist/artworks/{artwork}/edit', EditArtwork::class)->name('artist.artworks.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | Buyer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['buyer'])->group(function () {
        Route::get('/cart', Cart::class)->name('cart');
        Route::get('/checkout', Checkout::class)->name('checkout');
        Route::get('/buyer/orders', BuyerOrders::class)->name('buyer.orders');

        // Stripe Payment Flow
        Route::get('/checkout/stripe/{order}', [StripeController::class, 'checkout'])->name('checkout.stripe');

        Route::get('/payment/success', function (\Illuminate\Http\Request $request) {
            $order_id = $request->get('order_id');
            if ($order_id) {
                $order = \App\Models\Order::where('id', $order_id)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($order && $order->status === 'pending') {
                    // We update the status here for immediate UI feedback, 
                    // but the Webhook is the source of truth for final payment confirmation.
                    $order->update(['status' => 'processing']);

                    foreach ($order->items as $item) {
                        if ($item->artwork) {
                            $item->artwork->update(['status' => 'sold']);
                        }
                    }
                }
            }
            return redirect()->route('buyer.orders')->with('flash.banner', 'Payment successful! Your order is being processed.');
        })->name('payment.success');

        Route::get('/payment/cancel', function (\Illuminate\Http\Request $request) {
            $order_id = $request->get('order_id');
            if ($order_id) {
                $order = \App\Models\Order::where('id', $order_id)
                    ->where('user_id', Auth::id())
                    ->first();
                if ($order && $order->status === 'pending') {
                    // Optional: keep it pending so they can try again, or mark as cancelled.
                    // User requested to keep order unpaid.
                }
            }
            return view('payment.cancel', ['order_id' => $order_id]);
        })->name('payment.cancel');
    });
});

// Stripe Webhook (Public) - Outside auth groups
Route::post('/webhooks/stripe', [StripeController::class, 'webhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        'auth:sanctum',
        'verified',
        \App\Http\Middleware\CheckOnboarding::class,
    ]);
