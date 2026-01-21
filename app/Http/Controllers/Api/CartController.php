<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * View cart.
     */
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('artwork:id,title,price,image_path')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->artwork ? $item->artwork->price * $item->quantity : 0;
        });

        return response()->json([
            'cart_items' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Add to cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'artwork_id' => 'required|exists:artworks,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $artwork = Artwork::find($request->artwork_id);

        if ($artwork->status !== 'approved') {
            return response()->json(['message' => 'Artwork is not available for purchase.'], 422);
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('artwork_id', $request->artwork_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'artwork_id' => $request->artwork_id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return response()->json([
            'message' => 'Added to cart successfully',
            'cart_item' => $cartItem
        ]);
    }

    /**
     * Remove from cart.
     */
    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->where('artwork_id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Removed from cart successfully'
        ]);
    }
}
