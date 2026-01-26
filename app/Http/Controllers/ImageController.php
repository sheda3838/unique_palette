<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function showArtwork($id)
    {
        $artwork = Artwork::select('image_blob', 'image_mime')->findOrFail($id);

        if (!$artwork->image_blob) {
            abort(404);
        }

        return response($artwork->image_blob)
            ->header('Content-Type', $artwork->image_mime ?? 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=3600, must-revalidate');
    }

    public function showProfile($id)
    {
        $user = User::select('profile_image_blob', 'profile_image_mime')->findOrFail($id);

        if (!$user->profile_image_blob) {
            abort(404);
        }

        return response($user->profile_image_blob)
            ->header('Content-Type', $user->profile_image_mime ?? 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=3600, must-revalidate');
    }
}
