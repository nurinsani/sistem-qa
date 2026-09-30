<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE log_mutasi_audit MODIFY COLUMN jenis_mutasi ENUM('status', 'petugas', 'unit', 'tgl_awal', 'tgl_akhir') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE log_mutasi_audit MODIFY COLUMN jenis_mutasi ENUM('status', 'petugas', 'unit') NOT NULL");
    }
};
