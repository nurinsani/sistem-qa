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
        Schema::create('cs', function (Blueprint $table) {
            $table->string('cif', 10);
            $table->string('nama', 200);
            $table->string('kode_kel', 20);
            $table->bigInteger('bulat');
            $table->string('nama_kel', 170);
            $table->string('cao', 12);
            $table->bigInteger('os');
            $table->bigInteger('saldo_margin');
            $table->string('status_trans', 70);
            $table->bigInteger('nominal');
            $table->string('status_approve', 50)->nullable();
            $table->date('tgl_tagih')->nullable();

            // Composite Primary Key
            $table->primary(['cif', 'kode_kel', 'cao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cs');
    }
};
