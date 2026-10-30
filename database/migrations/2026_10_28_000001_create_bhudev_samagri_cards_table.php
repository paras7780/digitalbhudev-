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
        Schema::create('bhudev_samagri_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bhudev_profile_id')->constrained('bhudev_profiles')->cascadeOnDelete();
            $table->string('pooja_slug', 60)->default('ganpati')->index();
            $table->string('pooja_title', 180);
            $table->string('mantra_line', 120)->nullable()->default('|| શ્રી ગણેશાય નમઃ ||');
            $table->string('firm_title', 160)->nullable()->default('નારાયણ જ્યોતિષ');
            $table->string('left_priest_name', 120)->nullable();
            $table->string('left_priest_phone', 40)->nullable();
            $table->string('right_priest_name', 120)->nullable();
            $table->string('right_priest_phone', 40)->nullable();
            $table->longText('column1_items')->nullable();
            $table->longText('column2_items')->nullable();
            $table->string('yajman_name', 180)->nullable();
            $table->string('locale', 10)->default('gu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bhudev_samagri_cards');
    }
};
