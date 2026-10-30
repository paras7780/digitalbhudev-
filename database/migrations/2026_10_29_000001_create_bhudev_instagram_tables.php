<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bhudev_instagram_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bhudev_profile_id')
                ->unique()
                ->constrained('bhudev_profiles')
                ->cascadeOnDelete();
            $table->boolean('is_enabled')->default(false)->index();
            $table->string('connection_status', 30)->default('disconnected')->index(); // disconnected, connected, error
            $table->string('account_username', 120)->nullable();
            $table->string('account_id', 120)->nullable();
            $table->text('access_token')->nullable(); // Cast to encrypted
            $table->string('token_type', 40)->default('basic_display');
            $table->dateTime('token_expires_at')->nullable();
            $table->string('app_id', 120)->nullable();
            $table->text('app_secret')->nullable(); // Cast to encrypted
            $table->dateTime('last_sync_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });

        Schema::create('bhudev_instagram_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bhudev_profile_id')
                ->constrained('bhudev_profiles')
                ->cascadeOnDelete();
            $table->string('url', 500);
            $table->string('shortcode', 60)->index();
            $table->string('media_type', 30)->default('reel'); // reel, post, video, carousel
            $table->text('caption')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->string('author_name', 120)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['bhudev_profile_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bhudev_instagram_posts');
        Schema::dropIfExists('bhudev_instagram_settings');
    }
};
