<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    /**
     * List all approved artworks.
     */
    public function index()
    {
        $artworks = Artwork::where('status', 'approved')->with('user:id,name')->latest()->get();

        return response()->json([
            'artworks' => $artworks
        ]);
    }

    /**
     * View artwork details.
     */
    public function show(Artwork $artwork)
    {
        if ($artwork->status !== 'approved' && (!Auth::check() || (Auth::id() !== $artwork->user_id && Auth::user()->role !== 'admin'))) {
            return response()->json(['message' => 'Artwork not found or not approved.'], 404);
        }

        return response()->json([
            'artwork' => $artwork->load('user:id,name')
        ]);
    }

    /**
     * Create artwork (for artists).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('image')->store('artworks', 'public');

        $artwork = Artwork::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Artwork created successfully and is pending approval.',
            'artwork' => $artwork
        ], 201);
    }

    /**
     * Update artwork (for artists).
     */
    public function update(Request $request, Artwork $artwork)
    {
        if (Auth::id() !== $artwork->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($artwork->status === 'sold') {
            return response()->json(['message' => 'Sold artworks cannot be edited.'], 422);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        $artwork->update($validated);

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'image|mimes:jpg,jpeg,png|max:5120']);
            if ($artwork->image_path) {
                Storage::disk('public')->delete($artwork->image_path);
            }
            $artwork->image_path = $request->file('image')->store('artworks', 'public');
            $artwork->save();
        }

        return response()->json([
            'message' => 'Artwork updated successfully',
            'artwork' => $artwork
        ]);
    }

    /**
     * Delete artwork (for artists).
     */
    public function destroy(Artwork $artwork)
    {
        if (Auth::id() !== $artwork->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($artwork->status === 'sold') {
            return response()->json(['message' => 'Sold artworks cannot be deleted.'], 422);
        }

        if ($artwork->image_path) {
            Storage::disk('public')->delete($artwork->image_path);
        }

        $artwork->delete();

        return response()->json([
            'message' => 'Artwork deleted successfully'
        ]);
    }
}
