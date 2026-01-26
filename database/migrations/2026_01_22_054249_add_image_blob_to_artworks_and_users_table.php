<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            if (!Schema::hasColumn('artworks', 'image_mime')) {
                $table->string('image_mime', 50)->nullable()->after('image_path');
            }
        });

        // Use raw SQL for MySQL to get LONGBLOB (needed for larger images)
        if (config('database.default') === 'mysql') {
            if (!Schema::hasColumn('artworks', 'image_blob')) {
                DB::statement('ALTER TABLE artworks ADD image_blob LONGBLOB AFTER image_mime');
            }
        } else {
            Schema::table('artworks', function (Blueprint $table) {
                if (!Schema::hasColumn('artworks', 'image_blob')) {
                    $table->binary('image_blob')->nullable()->after('image_mime');
                }
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'profile_image_mime')) {
                $table->string('profile_image_mime', 50)->nullable()->after('profile_photo_path');
            }
        });

        if (config('database.default') === 'mysql') {
            if (!Schema::hasColumn('users', 'profile_image_blob')) {
                DB::statement('ALTER TABLE users ADD profile_image_blob LONGBLOB AFTER profile_image_mime');
            }
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'profile_image_blob')) {
                    $table->binary('profile_image_blob')->nullable()->after('profile_image_mime');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            if (Schema::hasColumn('artworks', 'image_blob')) {
                $table->dropColumn('image_blob');
            }
            if (Schema::hasColumn('artworks', 'image_mime')) {
                $table->dropColumn('image_mime');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_image_blob')) {
                $table->dropColumn('profile_image_blob');
            }
            if (Schema::hasColumn('users', 'profile_image_mime')) {
                $table->dropColumn('profile_image_mime');
            }
        });
    }
};
