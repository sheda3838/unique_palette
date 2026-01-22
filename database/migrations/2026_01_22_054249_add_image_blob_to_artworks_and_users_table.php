<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->string('image_mime')->nullable()->after('image_path');
        });
        
        // binary() in Laravel often maps to BLOB (64KB), so we use a raw statement for LONGBLOB
        DB::statement('ALTER TABLE artworks ADD image_blob LONGBLOB NULL AFTER image_path');

        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image_mime')->nullable()->after('profile_photo_path');
        });

        DB::statement('ALTER TABLE users ADD profile_image_blob LONGBLOB NULL AFTER profile_photo_path');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['image_mime', 'image_blob']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_image_mime', 'profile_image_blob']);
        });
    }
};
