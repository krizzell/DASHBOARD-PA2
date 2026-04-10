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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 1. Add temporary column untuk store converted NIS
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'nomor_induk_siswa_temp')) {
                $table->string('nomor_induk_siswa_temp', 20)->nullable()->after('nomor_induk_siswa');
            }
        });

        // 2. Update semua existing data dengan leading zeros
        DB::table('siswa')->get()->each(function ($siswa) {
            $paddedNIS = str_pad($siswa->nomor_induk_siswa, 6, '0', STR_PAD_LEFT);
            DB::table('siswa')
                ->where('nomor_induk_siswa', $siswa->nomor_induk_siswa)
                ->update(['nomor_induk_siswa_temp' => $paddedNIS]);
        });

        // 3. Drop foreign keys yang reference nomor_induk_siswa (safely)
        try {
            DB::statement('ALTER TABLE akun DROP FOREIGN KEY akun_nomor_induk_siswa_foreign');
        } catch (\Exception $e) {
            // FK might not exist
        }

        try {
            DB::statement('ALTER TABLE perkembangan DROP FOREIGN KEY perkembangan_nomor_induk_siswa_foreign');
        } catch (\Exception $e) {
            // FK might not exist
        }

        try {
            DB::statement('ALTER TABLE tagihan DROP FOREIGN KEY tagihan_nomor_induk_siswa_foreign');
        } catch (\Exception $e) {
            // FK might not exist
        }

        try {
            DB::statement('ALTER TABLE pembayaran DROP FOREIGN KEY pembayaran_nomor_induk_siswa_foreign');
        } catch (\Exception $e) {
            // FK might not exist
        }

        // 4. Drop primary key & unique constraint
        try {
            DB::statement('ALTER TABLE siswa DROP PRIMARY KEY');
        } catch (\Exception $e) {
            // Primary key might already be dropped
        }

        try {
            DB::statement('ALTER TABLE siswa DROP INDEX IF EXISTS siswa_nomor_induk_siswa_unique');
        } catch (\Exception $e) {
            // Index might not exist
        }

        // 5. Drop original column
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('nomor_induk_siswa');
        });

        // 6. Rename temp column ke original
        DB::statement('ALTER TABLE siswa CHANGE COLUMN nomor_induk_siswa_temp nomor_induk_siswa VARCHAR(20)');

        // 7. Add back primary key
        DB::statement('ALTER TABLE siswa ADD PRIMARY KEY (nomor_induk_siswa)');

        Schema::table('siswa', function (Blueprint $table) {
            $table->unique('nomor_induk_siswa');
        });

        // 8. Re-add foreign keys (safely)
        try {
            DB::statement('ALTER TABLE akun ADD CONSTRAINT akun_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // FK already exists or error
        }

        try {
            DB::statement('ALTER TABLE perkembangan ADD CONSTRAINT perkembangan_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // FK already exists or error
        }

        try {
            DB::statement('ALTER TABLE tagihan ADD CONSTRAINT tagihan_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // FK already exists or error
        }

        try {
            DB::statement('ALTER TABLE pembayaran ADD CONSTRAINT pembayaran_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // FK already exists or error
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this migration is complex, so we keep it simple
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement('ALTER TABLE akun DROP FOREIGN KEY akun_nomor_induk_siswa_foreign');
        DB::statement('ALTER TABLE perkembangan DROP FOREIGN KEY perkembangan_nomor_induk_siswa_foreign');
        DB::statement('ALTER TABLE tagihan DROP FOREIGN KEY tagihan_nomor_induk_siswa_foreign');
        DB::statement('ALTER TABLE pembayaran DROP FOREIGN KEY pembayaran_nomor_induk_siswa_foreign');

        DB::statement('ALTER TABLE siswa DROP PRIMARY KEY');
        DB::statement('ALTER TABLE siswa DROP INDEX IF EXISTS siswa_nomor_induk_siswa_unique');

        // Convert back to big integer - data might be lost if > 9223372036854775807
        DB::statement('ALTER TABLE siswa MODIFY nomor_induk_siswa BIGINT UNSIGNED');
        DB::statement('ALTER TABLE siswa ADD PRIMARY KEY (nomor_induk_siswa)');

        DB::statement('ALTER TABLE akun ADD CONSTRAINT akun_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');
        DB::statement('ALTER TABLE perkembangan ADD CONSTRAINT perkembangan_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');
        DB::statement('ALTER TABLE tagihan ADD CONSTRAINT tagihan_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');
        DB::statement('ALTER TABLE pembayaran ADD CONSTRAINT pembayaran_nomor_induk_siswa_foreign FOREIGN KEY (nomor_induk_siswa) REFERENCES siswa(nomor_induk_siswa) ON DELETE CASCADE');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
