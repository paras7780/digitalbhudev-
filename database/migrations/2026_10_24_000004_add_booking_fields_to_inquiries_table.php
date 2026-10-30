<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('type', 30)->default('contact')->index()->after('subject');
            $table->string('city', 100)->nullable()->after('type');
            $table->string('pooja_service', 150)->nullable()->after('city');
            $table->date('preferred_date')->nullable()->after('pooja_service');
            $table->foreignId('bhudev_profile_id')->nullable()->after('preferred_date')->constrained('bhudev_profiles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropForeign(['bhudev_profile_id']);
            $table->dropColumn(['phone', 'type', 'city', 'pooja_service', 'preferred_date', 'bhudev_profile_id']);
        });
    }
};
