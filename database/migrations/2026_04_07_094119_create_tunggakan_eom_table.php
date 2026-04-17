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
        Schema::create('tunggakan_eom', function (Blueprint $table) {
            $table->date('BUSS_DATE');
            $table->string('unit', 8);
            $table->string('cif', 8);
            $table->string('Cust_Short_name', 175);
            $table->bigInteger('os');
            $table->bigInteger('saldo_margin');
            $table->integer('ft');
            $table->bigInteger('nominal');
            $table->bigInteger('angsuran');
            $table->string('code_kel', 10);
            $table->string('cao', 10);
            $table->bigInteger('twm');
            $table->bigInteger('bulat')->nullable();
            $table->bigInteger('plafond')->nullable();

            // Composite Primary Key
            $table->primary(['cif', 'unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunggakan_eom');
    }
};
