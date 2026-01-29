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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('group_name')->nullable()->after('guard_name');
            $table->boolean('is_active')->default(true)->after('guard_name');
            $table->boolean('soft_delete')->default(false)->after('is_active');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('is_editable')->default(true)->after('guard_name');
            $table->boolean('is_deletable')->default(true)->after('is_editable');
            $table->boolean('is_available')->default(true)->after('is_deletable');
            $table->boolean('is_active')->default(true)->after('is_available');
            $table->boolean('soft_delete')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('group_name');
            $table->dropColumn('is_active');
            $table->dropColumn('soft_delete');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_editable');
            $table->dropColumn('is_deletable');
            $table->dropColumn('is_available');
            $table->dropColumn('is_active');
            $table->dropColumn('soft_delete');
        });
    }
};
