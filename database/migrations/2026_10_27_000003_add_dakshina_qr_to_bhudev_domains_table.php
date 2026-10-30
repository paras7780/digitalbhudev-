<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bhudev_domains', function (Blueprint $table) {
            $table->string('dakshina_qr_path')->nullable()->after('whatsapp_number');
            $table->string('upi_id', 100)->nullable()->after('dakshina_qr_path');
        });
    }

    public function down(): void
    {
        Schema::table('bhudev_domains', function (Blueprint $table) {
            $table->dropColumn(['dakshina_qr_path', 'upi_id']);
        });
    }
};
