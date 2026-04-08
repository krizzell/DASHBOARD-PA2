<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::table('siswa', function (Blueprint $table) {
            // Modify nomor_induk_siswa to not auto-increment
            $table->unsignedBigInteger('nomor_induk_siswa')->change();
        });

        // Add unique constraint if it doesn't exist
        try {
            DB::statement('ALTER TABLE siswa ADD UNIQUE(nomor_induk_siswa)');
        } catch (\Exception $e) {
            // Index might already exist
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            DB::statement('ALTER TABLE siswa DROP INDEX siswa_nomor_induk_siswa_unique');
        } catch (\Exception $e) {
            // Index might not exist
        }

        Schema::table('siswa', function (Blueprint $table) {
            $table->bigIncrements('nomor_induk_siswa')->change();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
