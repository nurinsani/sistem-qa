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
        Schema::create('fraud_alerts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cif', 10);
            $table->string('nama', 200);
            $table->date('tgl_tagih')->nullable();
            $table->string('flag_status', 20);
            $table->text('flag_reason');
            $table->dateTime('created_at');

            // index
            $table->index('cif', 'idx_cif');
            $table->index('tgl_tagih', 'idx_tgl_tagih');
            $table->index('flag_status', 'idx_flag_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraud_alerts');
    }
};
