<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bhudev_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('bhudev_profiles', 'availability')) {
                $table->string('availability', 255)->nullable()->after('bio');
            }
            if (!Schema::hasColumn('bhudev_profiles', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bhudev_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('bhudev_profiles', 'availability')) {
                $table->dropColumn('availability');
            }
            if (Schema::hasColumn('bhudev_profiles', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
