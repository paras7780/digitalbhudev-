<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bhudev_instagram_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('bhudev_instagram_settings', 'auto_play')) {
                $table->boolean('auto_play')->default(true)->after('is_enabled');
            }
        });

        Schema::table('bhudev_instagram_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('bhudev_instagram_posts', 'video_path')) {
                $table->string('video_path', 500)->nullable()->after('url');
            }
            if (!Schema::hasColumn('bhudev_instagram_posts', 'is_autoplay')) {
                $table->boolean('is_autoplay')->default(true)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bhudev_instagram_posts', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'is_autoplay']);
        });

        Schema::table('bhudev_instagram_settings', function (Blueprint $table) {
            $table->dropColumn('auto_play');
        });
    }
};
