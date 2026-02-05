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
        Schema::table('vaccines', function (Blueprint $table) {
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'QUARANTINE'])->default('ACTIVE')->after('manufacturer');
        });

        // Migrate existing data
        \DB::statement("UPDATE vaccines SET status = 'ACTIVE' WHERE is_active = 1");
        \DB::statement("UPDATE vaccines SET status = 'INACTIVE' WHERE is_active = 0");

        Schema::table('vaccines', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccines', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        // Reverse migration
        \DB::statement("UPDATE vaccines SET is_active = 1 WHERE status = 'ACTIVE' OR status = 'QUARANTINE'");
        \DB::statement("UPDATE vaccines SET is_active = 0 WHERE status = 'INACTIVE'");

        Schema::table('vaccines', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
