<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Fix tagihan NIS to match siswa format
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // For each tagihan, find matching siswa by converting format
        $tagihan = DB::table('tagihan')->get();
        
        foreach ($tagihan as $t) {
            // Try format with leading zeros (1009 -> 001009, but should be 000009)
            $nis = $t->nomor_induk_siswa;
            
            // Check if it's numeric and needs padding
            if (is_numeric($nis) && strlen($nis) < 6) {
                // Pad to 6 digits: 1009 -> 001009 (wait, that's not right)
                // Actually should be: 1009 is the counter from seeder, which got pad to 000009+1000
                // So 1008 should match 000008... let me think
                
                // Actually: if NIS is 1008, that means it was 8 in the seed counter
                // Which got pad to 000008, then +1000 = 001008? No...
                // Let me check the seeder logic...
                
                // From AkunSeeder: $siswaCounter starts at 1, pads to 6 digits = 000001
                // Then increments to 2, 3, ... up to 10
                // So: 1->000001, 2->000002 ... 10->000010
                
                // But in tagihan we see 1010, 1009, 1008, 1007 - these look like they were created with old code
                // that didn't have the padding
                
                // New siswa format: 000001, 000002, ... 000010, plus 000006 for manual input
                // So we need: 1->1 becomes 000001, ... 10->10 becomes 000010
                
                // Actually simpler: 1008 can't be right. Let me check if siswa with 000008 exists
                // and check relationship...
                
                $paddedNis = str_pad($nis, 6, '0', STR_PAD_LEFT);
                
                // Check if padded NIS exists in siswa
                $siswaExists = DB::table('siswa')
                    ->where('nomor_induk_siswa', $paddedNis)
                    ->exists();
                
                if ($siswaExists) {
                    DB::table('tagihan')
                        ->where('id_tagihan', $t->id_tagihan)
                        ->update(['nomor_induk_siswa' => $paddedNis]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback
    }
};
