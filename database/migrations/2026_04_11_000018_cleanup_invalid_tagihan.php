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
        // Delete semua tagihan yang nomor_induk_siswa-nya NULL atau tidak valid
        DB::statement('DELETE FROM tagihan WHERE nomor_induk_siswa IS NULL');
        DB::statement('DELETE FROM tagihan WHERE nomor_induk_siswa NOT IN (SELECT nomor_induk_siswa FROM siswa)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action
    }
};
