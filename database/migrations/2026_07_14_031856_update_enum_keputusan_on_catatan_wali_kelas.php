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
        // Add 'Naik ke kelas' to enum before updating data
        DB::statement("ALTER TABLE catatan_wali_kelas MODIFY COLUMN keputusan ENUM('Naik Kelas', 'Naik ke kelas', 'Tinggal Kelas') NULL;");
        
        // Update data
        DB::table('catatan_wali_kelas')
            ->where('keputusan', 'Naik Kelas')
            ->update(['keputusan' => 'Naik ke kelas']);
            
        // Remove 'Naik Kelas' from enum
        DB::statement("ALTER TABLE catatan_wali_kelas MODIFY COLUMN keputusan ENUM('Naik ke kelas', 'Tinggal Kelas') NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add 'Naik Kelas' back to enum
        DB::statement("ALTER TABLE catatan_wali_kelas MODIFY COLUMN keputusan ENUM('Naik Kelas', 'Naik ke kelas', 'Tinggal Kelas') NULL;");
        
        // Revert data
        DB::table('catatan_wali_kelas')
            ->where('keputusan', 'Naik ke kelas')
            ->update(['keputusan' => 'Naik Kelas']);

        // Remove 'Naik ke kelas' from enum
        DB::statement("ALTER TABLE catatan_wali_kelas MODIFY COLUMN keputusan ENUM('Naik Kelas', 'Tinggal Kelas') NULL;");
    }
};
