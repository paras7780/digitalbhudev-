<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bhudev_domains', function (Blueprint $table) {
            $table->text('about_intro')->nullable()->after('hero_tagline');
        });
    }

    public function down(): void
    {
        Schema::table('bhudev_domains', function (Blueprint $table) {
            $table->dropColumn('about_intro');
        });
    }
};
