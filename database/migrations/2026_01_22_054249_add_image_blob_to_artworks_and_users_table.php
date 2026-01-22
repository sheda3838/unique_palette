<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->string('image_mime', 50)->nullable()->after('image_path');
            $table->longBlob('image_blob')->nullable()->after('image_mime');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image_mime', 50)->nullable()->after('profile_photo_path');
            $table->longBlob('profile_image_blob')->nullable()->after('profile_image_mime');
        });
    }

    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['image_blob', 'image_mime']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_image_blob', 'profile_image_mime']);
        });
    }
};
