<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bhudev_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bhudev_profile_id')->constrained('bhudev_profiles')->cascadeOnDelete();
            $table->string('domain_name', 191)->unique()->index();
            $table->string('subdomain', 100)->nullable()->index();
            $table->string('tier', 30)->default('standard_saas'); // standard_saas, dedicated_custom
            $table->string('status', 30)->default('inquiry_submitted')->index(); // inquiry_submitted, quoted, paid, dns_pending, active, suspended
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->string('currency', 10)->default('INR');
            $table->text('bhudev_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_tagline')->nullable();
            $table->string('whatsapp_number', 30)->nullable();
            $table->string('theme_color', 30)->default('saffron');
            $table->string('separate_db_name', 100)->nullable();
            $table->string('separate_db_host', 100)->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhudev_domains');
    }
};
