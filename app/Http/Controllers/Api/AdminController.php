<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * List all users.
     */
    public function users()
    {
        $users = User::all();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * List all orders.
     */
    public function orders()
    {
        $orders = Order::with(['user:id,name', 'items.artwork'])->latest()->get();

        return response()->json([
            'orders' => $orders
        ]);
    }

    /**
     * Approve artwork.
     */
    public function approveArtwork(Artwork $artwork)
    {
        $artwork->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Artwork approved successfully',
            'artwork' => $artwork
        ]);
    }

    /**
     * Reject artwork.
     */
    public function rejectArtwork(Artwork $artwork)
    {
        $artwork->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Artwork rejected successfully',
            'artwork' => $artwork
        ]);
    }
}
