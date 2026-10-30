<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::table('users', function(Blueprint $table){ if(!Schema::hasColumn('users','phone')) $table->string('phone',25)->nullable()->unique(); if(!Schema::hasColumn('users','role')) $table->string('role',20)->default('bhudev')->index(); if(!Schema::hasColumn('users','is_active')) $table->boolean('is_active')->default(true)->index(); }); } public function down(): void { Schema::table('users',function(Blueprint $table){ if(Schema::hasColumn('users','phone')) $table->dropUnique(['phone']); foreach(['phone','role','is_active'] as $column) if(Schema::hasColumn('users',$column)) $table->dropColumn($column); }); } };
