<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Posts pulled automatically from a connected Instagram account ("library sync")
     * are tracked by their Instagram media id so repeated syncs update instead of duplicate.
     */
    public function up(): void
    {
        Schema::table('bhudev_instagram_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('bhudev_instagram_posts', 'source')) {
                $table->string('source', 20)->default('manual')->index()->after('bhudev_profile_id'); // manual | api
            }
            if (!Schema::hasColumn('bhudev_instagram_posts', 'ig_media_id')) {
                $table->string('ig_media_id', 64)->nullable()->after('source');
                $table->index(['bhudev_profile_id', 'ig_media_id']);
            }
            if (!Schema::hasColumn('bhudev_instagram_posts', 'media_url')) {
                $table->text('media_url')->nullable()->after('thumbnail_url');
            }
            if (!Schema::hasColumn('bhudev_instagram_posts', 'posted_at')) {
                $table->dateTime('posted_at')->nullable()->after('sort_order');
            }
            if (!Schema::hasColumn('bhudev_instagram_posts', 'synced_at')) {
                $table->dateTime('synced_at')->nullable()->after('posted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bhudev_instagram_posts', function (Blueprint $table) {
            $table->dropIndex(['bhudev_profile_id', 'ig_media_id']);
            $table->dropColumn(['source', 'ig_media_id', 'media_url', 'posted_at', 'synced_at']);
        });
    }
};
